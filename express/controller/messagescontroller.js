import { messagesModel } from "../models/models.messages.js";

export const sendToAdmin = async (req, res) => {
    const { message, sender_id, reciever_id } = req.body;
    try {
        const result = await messagesModel(sender_id, reciever_id, message)


        return res.status(201).json({ message: "Sent Message", data: result });
    } catch (error) {
        console.error("Error in sendToAdmin:", error);
        return res.status(500).json({ error: "Failed to send message" });
    }
}