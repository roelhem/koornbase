<template>

    <search-page src="/people/search" :cols="columns" :filters="filters" record-name="personen"></search-page>

</template>

<script>
    import PeopleSearchColBirthDate from "./col/birth-date";
    import PeopleSearchColId from "./col/id";
    import PeopleSearchColName from "./col/name";
    import PeopleSearchColNameShort from "./col/name-short";
    import PeopleSearchColMembershipStatus from "./col/membership-status";
    import PeopleSearchColLink from "./col/link";
    import PeopleSearchColAvatar from "./col/avatar";

    import PeopleSearchFilterMembershipStatus from "./filter/membership-status";

    import SearchPage from "../../search/page";

    export default {
        name: "people-search-page",
        props: {},
        data: function() {
            return {
                columns: [
                    {
                        label:null,
                        name:'Avatar',
                        key:'people-search-col-avatar',
                        component: PeopleSearchColAvatar,
                        visible: true,
                    },
                    {
                        label:'ID',
                        name:'ID',
                        key:'people-search-col-id',
                        component: PeopleSearchColId,
                        visible: false,
                    },
                    {
                        label:'Naam',
                        name:'Naam',
                        key:'people-search-col-name',
                        component: PeopleSearchColName,
                        visible: true,
                    },
                    {
                        label:'Korte Naam',
                        name:'Korte Naam',
                        key:'people-search-col-name-short',
                        component: PeopleSearchColNameShort,
                        visible: false,
                    },
                    {
                        label:'Geboortedatum',
                        name:'Geboortedatum en leeftijd',
                        key:'people-search-col-birth-date',
                        component: PeopleSearchColBirthDate,
                        visible: true,
                    },
                    {
                        label:'Status',
                        name:'Lidstatus',
                        key:'people-search-col-membership-status',
                        component: PeopleSearchColMembershipStatus,
                        visible: true
                    },
                    {
                        label:null,
                        name:'Links',
                        key:'people-search-col-link',
                        component: PeopleSearchColLink,
                        visible: true
                    }
                ],
                filters: [
                    {
                        title:'Lidstatus',
                        key:'people-search-filter-membership-status',
                        param:'membership_status',
                        active: true,
                        value: [],
                        component:PeopleSearchFilterMembershipStatus
                    },
                ],
            }
        },
        components: {
            SearchPage,
        },
    }
</script>

<style scoped>

</style>