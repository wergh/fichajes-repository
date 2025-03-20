# Proyecto Symfony + Vue 3

Este proyecto implementa una arquitectura **hexagonal** con **DDD** y sigue los principios **SOLID**. Utiliza **Symfony** para el backend y **Vue 3** para el frontend, ambos dockerizados en un mismo repositorio.

## Tecnologías

- **Backend:** Symfony
- **Frontend:** Vue 3
- **Base de Datos:** MySQL
- **Contenedores:** Docker + Docker Compose

## Características

- Diseño DDD + Arquitectura Hexagonal
- Principios SOLID
- Implementación de CQRS.
- Validaciones de reglas de negocio
- Base de datos MySQL en Docker.
- Integración de Makefile para simplificar comandos.

## Instalación y Uso

### Requisitos

- Docker y Docker Compose
- Make (opcional, pero recomendado)

Para entornos Windows es necesario tener instalado el Docker Desktop. Una vez se tenga instalado e iniciado correctamente, con los siguientes comandos se puede desplegar el proyecto.

### Construir y levantar el entorno

Con Make:

```sh
make up
```

Con Docker Compose:

```sh
docker-compose up -d --build
```

### Apagar el entorno

Con Make:

```sh
make down
```

Con Docker Compose:

```sh
docker-compose down
```

### Apagar y eliminar volúmenes

Con Make:

```sh
make down-v
```

Con Docker Compose:

```sh
docker-compose down -v
```

### Aplicar migraciones

Con Make:

```sh
make migrate
```

Con Docker Compose:

```sh
docker-compose exec backend php bin/console doctrine:migrations:migrate
```

### Validar el esquema de la base de datos

Con Make:

```sh
make schema-validate
```

Con Docker Compose:

```sh
docker-compose exec backend php bin/console doctrine:schema:validate
```

### Ejecutar tests

#### Test de backend

Con Make:

```sh
make test-backend
```

Con Docker Compose:

```sh
docker-compose exec backend php bin/phpunit
```

#### Test de frontend

Con Make:

```sh
make test-frontend
```

Con Docker Compose:

```sh
docker-compose exec frontend npm run test:unit
```

### Modo de Uso

Docker desplegará los siguientes contenedores:

- **Contenedor de Vue 3** - [http://localhost:8080/](http://localhost:8080/)
- **Contenedor de Symfony** - [http://localhost:8000/](http://localhost:8000/)
- **Contenedor de MySQL** - `localhost:3306`
- **Contenedor de PhpMyAdmin** - [http://localhost:8081/](http://localhost:8081/)

Se ha añadido un cuarto contenedor con PhpMyAdmin por si se desea poder consultar la base de datos de manera más cómoda.

## Estructura del Proyecto

```bash
/
├── backend/              # Código fuente de Symfony
├── frontend/             # Código fuente de Vue 3
├── docker-compose.yaml   # Configuraciones de Docker
├── Makefile              # Comandos de automatización
└── README.md             # Documentación del proyecto
```

## Descripción del proyecto

Como indicábamos inicialmente, el proyecto está dividido en Backend y Frontend. A continuación se analizan las características fundamentales de cada uno de ellos.

### Backend

El backend está construido con Symfony 7.2 y requiere una versión de PHP mínima 8.2. Además del framework de Symfony, se ha instalado PhpUnit para los tests unitarios y bypass-finals para ciertos mockeos.

Como se mencionó anteriormente, está construido en arquitectura Hexagonal. A continuación se detalla cada capa:

#### Capa Domain

Aquí encontramos toda la lógica de negocio, con sus entidades fundamentales:
- **User** y **WorkEntry**: Entidades principales
- **WorkEntryLogs**: Entidad adicional para la trazabilidad de las actualizaciones de los WorkEntry

En cada entidad, además de sus propiedades, getters y setters, tenemos sus métodos propios que no requieren interconexión con otras entidades. Por ejemplo, en WorkEntry tenemos métodos como `isOpen()` que indica si un WorkEntry está abierto o finalizado, el método `close()` que finaliza el WorkEntry, validaciones propias, etc.

Se ha creado un ValueObject con el ID de cada una de las entidades principales, dado que se utilizan UUIDs. La implementación de estos se realiza utilizando la arquitectura hexagonal y la abstracción para respetar los principios SOLID. Se crea una interfaz a nivel dominio que se implementa en la capa de infraestructura para interactuar con las librerías de Symfony y generar el UUID.

También en esta capa tenemos:
- **Excepciones de Dominio**: Aquellas vinculadas a la lógica de dominio
- **Interfaces de los repositorios**: Read y Write por separado para cumplir con el patrón CQRS
- **Servicios específicos**: Que validan reglas de negocio y por lo tanto deben estar en la capa de dominio
- **Evento de dominio**: WorkEntryUpdateEvent, que se despacha en la capa de aplicación siempre que se actualiza un WorkEntry

#### Capa Application

En esta capa se crea la lógica principal de la aplicación y sirve de nexo entre la capa de Infraestructura y la capa de Dominio.

En esta capa tenemos:
- **Commands**: Clases que transportan los objetos entrantes hacia la capa de dominio
- **DTOs**: Se encargan de transportar los objetos salientes de la capa de dominio a la de infraestructura
- **Mappers**: Transforman entidades en DTOs para preservar la integridad de la capa de dominio
- **Validadores**: Interfaces que se implementan en la capa de Infraestructura
- **Casos de uso**: Cada uno con una función claramente definida en su nombre

Se han implementado excepciones para cada posible error que se pueda cometer en los casos de uso, todas ellas recogidas en la capa de infraestructura.

#### Capa Infrastructure

La capa de infraestructura se divide en:

**Persistencia**, donde implementamos toda la parte de Doctrine:
- **Mapping**: Lo que lee Symfony para crear los registros en la base de datos
- **Types personalizados**: Concretamente para UserId y WorkEntryId
- **Implementaciones**: De las interfaces de dominio para User, WorkEntry y WorkEntryLog

**Symfony**:
- Contiene la implementación de la interfaz de dominio para generar los UUIDs

**EventListener**:
- Listener del evento de dominio, encargado de recoger el evento generado y almacenar la información sobre el cambio
- Para cada cambio que se realiza se almacena la hora de entrada y salida antes del cambio, así como la hora de la edición y quién la realizó

**Validator**:
- Implementación de las interfaces de la capa de aplicación, que interactúan con el Validator de Symfony
- Validaciones personalizadas como:
    - **UserExists**: Comprueba si el UUID proporcionado pertenece a algún usuario registrado
    - **DateTimeImmutableType**: Verifica si la fecha proporcionada es un DateTimeImmutable
    - **StartDateBeforeEndDate**: Comprueba que un fichaje no puede empezar después de terminar

**API**:
- **Controllers**: Uno para cada endpoint respetando el principio de responsabilidad única
- **Requests**: Validan que los campos proporcionados son los requeridos para cada endpoint

#### Enfoque de validaciones

Las validaciones se plantean en 3 niveles distintos:

1. **Capa de Infraestructura**:
    - Validación de requests y accesos al endpoint
    - Validación de tokens de usuario
    - Verificación de permisos de acceso
    - Comprobación de campos requeridos

2. **Capa de Aplicación** (interfaces en aplicación implementadas en infraestructura):
    - Validación de consistencia de datos
    - Verificación de UUIDs válidos
    - Correspondencia con entidades concretas
    - Validaciones lógicas como startTime y endTime

3. **Capa de Dominio**:
    - Validación de reglas de negocio
    - Por ejemplo: no crear un fichaje nuevo cuando hay uno abierto
    - Validación de que al editar un fichaje no se solape con otro existente

#### Testing

Los tests están organizados igual que la aplicación:

- **Capa de Dominio**: Tests de entidades, métodos privados y constructores
- **Capa de Aplicación**: Tests de Use Cases, tanto el funcionamiento correcto como cada posible error
- **Capa de Infraestructura**: Tests de validación de tokens, permisos de usuario y manejo correcto de requests

### Frontend

El Frontend está construido con Vue 3 y TypeScript. Para el frontend se ha utilizado una estructura más estándar:

- **assets**: En este proyecto están vacíos, pero normalmente contienen imágenes, estilos, etc.
- **components**:
    - En la carpeta principal están los componentes principales
    - En **common** se almacenan componentes reutilizables como selects, inputs, botones, etc.
- **composables**: Contiene el archivo userTimer.ts con funciones para encender, apagar y resetear el cronómetro que registra el tiempo de trabajo
- **config**: Archivos de configuración, en este caso la configuración de axios y los manejos de las request y responses
- **interfaces**: Declaraciones de todas las interfaces utilizadas en la aplicación
- **servicios**: Definición de los endpoints de cada tipo (user y workentry)
- **stores**: Donde iría la parte de Pinia si tuviéramos store en el proyecto
- **utils**: Archivos auxiliares con funciones y definiciones de tipos que ayudan en el desarrollo de la aplicación

#### Funcionamiento de la aplicación

La aplicación funciona de la siguiente manera:

1. Al iniciar, obtiene los usuarios conectados
2. En caso de no haber ninguno, dibuja el componente EmptyUsers, que lanza el modal de crear usuarios cuando se presiona el botón
3. Si hay usuarios, se añaden al selector de usuarios en la esquina superior derecha
4. Al lado del selector se encuentra el botón de Entrar/Salir y el temporizador de lo que lleva fichado a lo largo del día
5. Debajo se muestra la lista de fichajes (de haber alguno)
6. En cada fichaje tenemos la opción de editar o eliminar con el botón correspondiente:
    - Al editar, aparecen selectores en la hora para cambiarlos y los botones pasan a ser guardar y cancelar
    - Al eliminar, se procede a borrar el workentry
    - Ambas opciones tienen un botón de confirmación con la librería SweetAlert

También se ha añadido la librería toast para las notificaciones.

En la carpeta de test tenemos una serie de tests que prueban a grandes rasgos toda la aplicación.

## Licencia

Este proyecto está bajo la licencia MIT.
