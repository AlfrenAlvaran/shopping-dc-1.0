import nodemailer from 'nodemailer';
import { SIGNUP } from './mailtamplate.js';

export const sign_up = async (req, res) => {
    try {
        const { email, name } = req.body; 
        console.log(req.body)
    
        const transporter = nodemailer.createTransport({
            service: 'gmail',
            auth: {
                user: process.env.EMAIL,
                pass: process.env.PASSWORD_APP
            }
        });

        const mailOptions = {
            from: email,
            to: process.env.EMAIL,
            subject: "Register Customer",
            html: SIGNUP.replace('{name}', name).replace('{email}', email)
        };

        const response = await transporter.sendMail(mailOptions);
        console.log('Email Sent: ', response.messageId);

        res.status(200).send({ message: "Email sent successfully" });
    } catch (error) {
        console.error('Error sending email:', error);
        res.status(500).send({ message: "Failed to send email", error });
    }
};


