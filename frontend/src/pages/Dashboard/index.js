import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import api from '../../services/api';

const Login = () => {

    const [users, setUsers] = useState([]);
    //refactor find by cookie
    const token = '';
    const config = {
        headers: { Authorization: `Bearer ${token}` }
    };

    const listUsers = () => {
        api.get('api/users').then((response) => {
            setUsers(response.data);
        })
    }

    useEffect(() => {
        (async () => {
            const { data } = await api.get('/api/users', config);
            setUsers(data);
        })();
    }, []);

    return (
        <>
            <div className='btn'>
                <Link to='/'>Login</Link>
            </div>
            <h1>Bem vindo ao dashboard!!!</h1>
            <button onClick={listUsers} className='btn'>Listar usuários</button>
            <ul>
                {
                    users.map(user => (
                        <li key={user.id}>{user.name}</li>
                    ))
                }
            </ul>
        </>
    )
}


export default Login;
