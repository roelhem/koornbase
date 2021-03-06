
export const GROUP_STYLES = [
    {
        name:'group-default',
        label:'Standaard',
        icon:{
            fa:'users',
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-system',
        label: 'Systeem',
        color:'gray-dark',
        icon:{
            fa:'server'
        },
        tag:{
            alwaysShowAvatar:true,
            color:'gray-dark',
        }
    },
    {
        name:'group-master',
        label:'Master',
        color:'purple',
        icon:{
            fa:'terminal'
        },
        tag:{
            alwaysShowAvatar:true,
            color:'purple',
        }
    },
    {
        name:'group-debug',
        label:'Debug',
        color:'gray-dark',
        icon:{
            fa:'bug'
        },
        tag:{
            alwaysShowAvatar:true,
            color:'gray',
        }
    },
    {
        name:'group-develop',
        label:'Ontwikkelen',
        color:'gray-dark',
        icon:{
            fa:'code'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-important',
        label:'Belangrijk',
        color:'red',
        icon:{
            fa:'sun-o'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-structure',
        label:'Structureel',
        color:'pink',
        icon:{
            fa:'cubes'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-primary',
        label:'Primair',
        color:'orange',
        icon:{
            fa:'puzzle-piece'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-secondary',
        label:'Secundair',
        color:'yellow',
        icon:{
            fa:'puzzle-piece'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-skill',
        label:'Vaardigheid',
        color:'green',
        icon:{
            fa:'support'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-friend',
        label:'Vriend',
        color:'teal',
        icon:{
            fa:'beer'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-tag',
        label:'Label',
        color:'lime',
        icon:{
            fa:'tag',
            fe:'tag'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    },
    {
        name:'group-study',
        label:'Studie',
        color:'indigo',
        icon:{
            fa:'graduation-cap'
        },
        tag:{
            alwaysShowAvatar:true,
        }
    }
];

export const PERSON_STYLE = {
    name: 'person-default',
    label: 'Persoon',
    avatar: {
        color:'blue'
    },
};

export const USER_STYLE = {
    name: 'user-default',
    label: 'Gebruiker',
    avatar: {
        color:'gray'
    },
};

export default [ PERSON_STYLE, USER_STYLE ].concat(GROUP_STYLES);