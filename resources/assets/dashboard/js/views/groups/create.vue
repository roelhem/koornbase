<template>

    <b-container>

        <tabler-page-header
                title="Nieuwe groep aanmaken"
                :breadcrumb="[
                    {icon:'database', text:'Administratie', active:true},
                    {text:'Groepen', to:{name: 'db.groups.index'}},
                    {text:'Nieuwe Groep', active:true}
                ]"
        />

        <create-group-form />


    </b-container>

</template>

<script>
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import CreateGroupForm from "../../components/features/crud/CreateGroupForm";

    export default {
        components: {
            CreateGroupForm,
            TablerPageHeader},
        name: "view-groups-create"
    }
</script>

<style scoped>

</style>