import express from 'express';
import { sendToAdmin } from '../controller/messagescontroller.js';

const messageRouter = express.Router()

messageRouter.post('/sendMessage', sendToAdmin)

export default messageRouter