import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

import Dashboard from './pages/Dashboard/index';
import Login from './pages/Login/index';

const Routes = () => {
  return (
    <BrowserRouter>
      <Switch>
        <Route path="/" exact component={Login} name="Login" />
        <Route path="/dashboard" component={Dashboard} name="Dashboard" />
      </Switch>
    </BrowserRouter>
  );
}

export default Routes;