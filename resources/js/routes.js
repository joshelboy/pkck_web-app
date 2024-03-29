import Home from './components/Home';
import About from './components/About';
import NotFound from './components/NotFound';

export default{
    mode: 'history',
    routes: [
        {
            path: '*',
            component: NotFound
        },
        {
            path: '/',
            component: Home
        },
        {
            path: '/about',
            component: About
        },
        {
            name: 'edit',
            path: '/edit/:id',
            component: BBQ_Calendar/EditEvent
        }
    ]
}