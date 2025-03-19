FROM node:18-alpine

WORKDIR /app

# Copiar los archivos de configuración de dependencias primero
COPY package*.json ./

# Instalar dependencias
RUN npm install

# Copiar el resto del código fuente
COPY . .

# Exponer el puerto que utiliza el servidor de desarrollo de Vue
ENV VITE_PORT=8080
ENV HOST=0.0.0.0
EXPOSE 8080

# Iniciar el servidor de desarrollo (para entorno de desarrollo)
CMD ["npm", "run", "dev", "--", "--port", "8080", "--host", "0.0.0.0"]

# Para producción, podrías usar:
# RUN npm run build
# Y luego servir los archivos estáticos con un servidor web como nginx
