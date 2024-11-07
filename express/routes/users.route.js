import express from 'express'
import { sign_up } from '../controller/signup.js'


const USER = express.Router()

USER.post('/user', sign_up)


export default USER