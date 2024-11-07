import mysql from 'mysql2/promise';

export const DBConnection = async () => {
    try {
        const OpenConnection = await mysql.createConnection({
            host: process.env.DNS,
            user: process.env.USER,
            password: process.env.DBPASS,
            database: process.env.DB
        })

        return OpenConnection
    } catch (e) {
        console.error("Server Connection Error:", e);
        throw e;    
    }
}