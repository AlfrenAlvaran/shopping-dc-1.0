import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import USER from './routes/users.route.js';
import { DBConnection } from './database/mysql.connection.js';
import messageRouter from './routes/message.route.js';


dotenv.config()

const app = express();
const PORT = process.env.PORT || 5000;

app.use(express.json())
app.use(cors())

let connection
(async ()=> {
  connection = await DBConnection()
})() // Basta promise to



app.use('/api', USER)
app.use('/api', messageRouter)


app.get('/', (req, res)=> {
  res.send("Online")
})

// Start Server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
  });
  