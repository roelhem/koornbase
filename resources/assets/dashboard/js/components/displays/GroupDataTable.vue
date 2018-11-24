<template>

    <b-card no-body>
        <b-table class="card-table" v-bind="tableProps" v-on="tableListeners">

            <template slot="category" slot-scope="{item:group}">
                <base-stamp v-if="group.category"
                            :default-style="group.category.style"
                            @mouseover="$emit('highlight-category', group.category.id)"
                            @mouseleave="$emit('highlight-category', null)"
                            class="muted-stamp"
                />
            </template>

            <template slot="name" slot-scope="{item:group}">
                <span v-b-tooltip.hover.bottom="group.description || ''">{{ group.name }}</span>
            </template>

            <template slot="persons" slot-scope="{item:group}">
                <group-persons-avatar-list :persons="group.persons" stacked />
            </template>

            <template slot="actions" slot-scope="{ item:group }">
                <router-link class="icon" :to="{name:'db.groups.view', params:{id:group.id}}">
                    <base-icon icon="more-vertical" from="fe" />
                </router-link>
            </template>

        </b-table>
    </b-card>

</template>

<script>

    import gql from 'graphql-tag';
    import dataTableMixin from "../../mixins/dataTableMixin";
    import BaseStamp from "./BaseStamp";
    import BaseIcon from "./BaseIcon";
    import BaseAvatarList from "./BaseAvatarList";
    import GroupPersonsAvatarList from "./GroupPersonsAvatarList";

    export default {
        components: {
            GroupPersonsAvatarList,
            BaseAvatarList,
            BaseIcon,
            BaseStamp},
        name: "group-data-table",

        mixins:[dataTableMixin],

        rowFragment: gql`
            fragment GroupDataTableRow on Group {
                id
                name
                description
                category {
                    id
                    style
                }
                ...GroupPersonsAvatarList
            }
            ${GroupPersonsAvatarList.fragment}
        `,

        columns:{
            category:{
                label:'',
                name:'Category',
                visible:true,
                sortable:true,
                tdClass:'p-2',
                thStyle:{'width':'1px'},
            },
            id:{
                label:'ID',
                visible:false,
                sortable:true,
            },
            name:{
                label:'Naam',
                sortable:true,
                visible:true
            },
            shortName:{
                label:'Korte naam',
                sortable:true,
                visible:false
            },
            memberName:{
                label:'Titel',
                sortable:true,
                visible:false
            },
            persons:{
                label:'Groepsleden',
                tdClass:'p-2',
                sortable:false,
                visible:true,
            },
            actions:{
                label:'',
                visible:true,
                thStyle:{'width':'1px'}
            }
        }
    }
</script>

<style scoped>

</style>