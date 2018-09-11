import Vue from 'vue';
import VueRouter from 'vue-router';

import PageMe from '../../views/me/overview';
import PageApps from '../../views/oauth/apps/index';
import PageUsers from '../../views/users/index';
import PageMeKoornbeursData from '../../views/me/overview.koornbeurs';
import PageMePersonalData from '../../views/me/overview.personal';
import PagePerson from '../../views/persons/view';
import PagePersonContact from '../../views/persons/view.contact';
import PagePersonDebug from '../../views/persons/view.debug';
import PagePersonList from '../../views/persons/index';
import PagePersonOverview from '../../views/persons/view.overview';
import PagePersonMembership from '../../views/persons/view.membership';
import PageOAuthClients from '../../views/oauth/clients/index';
import PageOAuthClient from '../../views/oauth/clients/view';

Vue.use(VueRouter);

export const routes = [

    {
        path:'/home',
        alias:'/',
        name:'home',
        component: require('../../views/home/index'),
    },


    {
        path:'/me',
        name:'me.overview',
        component: require('../../views/me/overview'),
        children: [
            {
                path:'personal',
                name:'me.overview.personal',
                component: require('../../views/me/overview.personal'),
            },
            {
                path:'koornbeurs',
                name:'me.overview.koornbeurs',
                component: require('../../views/me/overview.koornbeurs'),
            }
        ],
    },


    {
        path:'/oauth/apps',
        name:'oauth.apps.index',
        component: require('../../views/oauth/apps/index'),
    },
    {
        path:'/oauth/clients',
        name:'oauth.clients.index',
        component: require('../../views/oauth/clients/index'),
    },
    {
        path:'/oauth/clients/create',
        name:'oauth.clients.create',
        component: require('../../views/oauth/clients/create'),
    },
    {
        path:'/oauth/clients/:id/update',
        name:'oauth.clients.update',
        component: require('../../views/oauth/clients/update'),
    },
    {
        path:'/oauth/clients/:id/request-personal-token',
        name:'oauth.clients.request-personal-token',
        component: require('../../views/oauth/clients/request-personal-token'),
        props:true,
    },
    {
        path:'/oauth/clients/:id',
        name:'oauth.clients.view',
        component: require('../../views/oauth/clients/view'),
        props:true,
    },


    {
        path:'/db/persons',
        name:'db.persons.index',
        component: require('../../views/persons/index'),
    },
    {
        path:'/db/persons/:id',
        name:'db.persons.view',
        component: require('../../views/persons/view'),
        props:true,
        children: [
            {
                path:'contact',
                name:'db.persons.view.contact',
                component: require('../../views/persons/view.contact'),
            },
            {
                path:'debug',
                name:'db.persons.view.debug',
                component: require('../../views/persons/view.debug'),
            },
            {
                path:'membership',
                name:'db.persons.view.membership',
                component: require('../../views/persons/view.membership'),
            },
            {
                path:'overview',
                name:'db.persons.view.overview',
                component: require('../../views/persons/view.overview'),
            },
        ]
    },

    {
        path:'/db/groups',
        name:'db.groups.index',
        component: require('../../views/groups/index'),
    },
    {
        path:'/db/groups/:id',
        name:'db.groups.view',
        component: require('../../views/groups/view'),
        props:true,
    },


    {
        path:'/users',
        name:'users.index',
        component: require('../../views/users/index'),
    },
    {
        path:'/users/create',
        name:'users.create',
        component: require('../../views/users/create'),
    }
];

/*
export const routes = [

    {
        name:'home',
        path:'/home',
        alias:'/',
        component: require('./components/pages/home/index'),
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
            label: 'OAuth',
            icon: {
                'fe':'globe',
                'fa':'globe'
            },
            headerNavbar: true
        }
    },

    {
        name:'oauth.clients',
        path:'/oauth',
        component: PageOAuthClients,
        meta: {
            label: 'OAuth Clients',
            icon: {
                'fe':'globe',
                'fa':'globe'
            },
            headerNavbar: true
        }
    },

    {
        name:'oauth.clients.view',
        path:'/oauth/:id',
        component: PageOAuthClient,
        props:true
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

];*/

export const router = new VueRouter({
    routes
});

export default router;