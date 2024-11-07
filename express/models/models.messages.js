import { DBConnection } from "../database/mysql.connection.js"

export const messagesModel = async (send_id, reciever_id, message) => {
    const connection = await DBConnection()
    try {
        const SQL = 'INSERT INTO `messages`(`sender_id`, `receiver_id`, `message`) VALUES (?, ?, ?)';
        const [qeury] = await connection.execute(SQL, [send_id, reciever_id, message]);
        return qeury;
    } catch (e) {
        console.error("Database Error", e);
        throw e;
    }
}