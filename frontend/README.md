
## Installation

To install the repository, follow these steps. We will assume that the branch has already been merged.

1. **Clone the repository**  
   To clone the repository, use the following command:

   ```bash
   git clone git@github.com:wergh/vue3-fichajes-frontend.git
   ```
2. **Rename .env.example to .env**  
   After cloning, rename the .env.example file to .env In this document, you will see two variables that slightly 
   modify the behavior of the application.
    ```bash
   mv .env.example .env
   ```
3. **Install and compile node_modules**  
   Run the following command to install and compile the necessary dependencies:

   ```bash
   npm i && npm run dev
   ```
   
## Tets

To run the test you can do it with the command
   ```bash
   npm run test:unit
   ```

