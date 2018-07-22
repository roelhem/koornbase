import Vue from 'vue';
import VueRouter from 'vue-router';

import PageHome from './components/pages/PageHome';
import PageMe from './components/pages/PageMe';
import PageApps from './components/pages/PageApps';
import PageUsers from './components/pages/PageUsers';
import PageMeKoornbeursData from './components/pages/PageMeKoornbeursData';
import PageMePersonalData from './components/pages/PageMePersonalData';
import PagePerson from './components/pages/PagePerson';
import PagePersonContact from './components/pages/PagePersonContact';
import PagePersonDebug from './components/pages/PagePersonDebug';
import PagePersonList from './components/pages/PagePersonList';
import PagePersonOverview from './components/pages/PagePersonOverview';
import PagePersonMembership from './components/pages/PagePersonMembership';

Vue.use(VueRouter);

export const routes = [

    {
        name:'home',
        path:'/home',
        alias:'/',
        component: PageHome,
        meta:{
            label:'Home',
            icon:{
                'fe':'home',
                'fa':'home'
            },
            headerNavbar:true
        }
    },

    {
        name:'persons.index',
        path:'/persons',
        component: PagePersonList,
        meta:{
            label:'Personen',
            icon:{
                'fe':'users',
                'fa':'users'
            },
            headerNavbar:true
        }
    },

    {
        name:'persons.view',
        path:'/persons/:id',
        component: PagePerson,
        meta:{
            label:'Persoon',
        },
        props: true,
        children: [
            {
                path:'',
                redirect:'overview',
            },
            {
                name:'persons.view.overview',
                path:'overview',
                component:PagePersonOverview,
            },
            {
                name:'persons.view.contact',
                path:'contact',
                component:PagePersonContact,
            },
            {
                name:'persons.view.membership',
                path:'membership',
                component:PagePersonMembership,
            },
            {
                name:'persons.view.debug',
                path:'debug',
                component:PagePersonDebug
            }
        ]
    },

    {
        name:'apps',
        path:'/apps',
        component: PageApps,
        meta: {
            label: 'Apps',
            icon: {
                'fe':'globe',
                'fa':'globe'
            },
            headerNavbar: true
        }
    },

    {
        name:'users',
        path:'/users',
        component: PageUsers,
        meta: {
            label: 'Gebruikers',
            headerNavbar:true
        }

    },

    {
        name:'me',
        path:'/me',
        component: PageMe,
        meta:{
            label:'Mijn Gegevens',
        },
        children: [
            {
                path:'',
                redirect:'personal',
            },
            {
                name:'me.personal',
                path:'personal',
                component:PageMePersonalData,
            },
            {
                name:'me.kb',
                path:'kb',
                component:PageMeKoornbeursData,
            }
        ]
    }

];

export const router = new VueRouter({
    routes
});

export default router;