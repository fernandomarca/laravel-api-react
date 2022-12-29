import React, { useState } from 'react';
import { Link } from 'react-router-dom';

import api from '../../services/api';

const Login = () => {

    const [data, setData] = useState({
        email: '',
        password: ''
    });

    function handleInputChange(event) {
        const { name, value } = event.target;
        setData({ ...data, [name]: value })
    }

    const handleLogin = async (e) => {
        e.preventDefault();
        await api.get('sanctum/csrf-cookie');
        await api.post('login', data,);
    }

    return (
        <>
            <div className='login'>
                <form onSubmit={handleLogin}>
                    <input type="text" name='email' onChange={handleInputChange} />
                    <input type="text" name='password' onChange={handleInputChange} />
                    <button type="submit" className='btn'>Login</button>
                </form>

                <div className='btn'>
                    <Link to='/dashboard'>Dashboard</Link>
                </div>
            </div>
        </>

    )
}


export default Login;
