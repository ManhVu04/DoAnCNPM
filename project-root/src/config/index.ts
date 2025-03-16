export const config = {
    database: {
        host: process.env.DB_HOST || 'localhost',
        port: process.env.DB_PORT || 5432,
        user: process.env.DB_USER || 'user',
        password: process.env.DB_PASSWORD || 'password',
        database: process.env.DB_NAME || 'database_name',
    },
    server: {
        port: process.env.PORT || 3000,
    },
    environment: process.env.NODE_ENV || 'development',
};