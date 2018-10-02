<template>

    <div>

        <b-container class="mb-8">

            <b-row>
                <b-col>
                    <tabler-form-group label="Groepen">
                        <group-select v-model="groups" multiple />
                    </tabler-form-group>

                    <tabler-form-group label="Groepscategoriëen">
                        <group-category-select v-model="groupCategories" multiple />
                    </tabler-form-group>

                    <tabler-form-group label="Personen">
                        <person-select v-model="persons" multiple />
                    </tabler-form-group>

                    <tabler-form-group label="Users">
                        <user-select v-model="users" multiple />
                    </tabler-form-group>

                    <tabler-form-group label="Certificaatcategoriëen">
                        <certificate-category-select v-model="certificateCategories" multiple />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Groep">
                        <group-select v-model="group" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Groepscategorie">
                        <group-category-select v-model="groupCategory" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Persoon">
                        <person-select v-model="person" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Gebruiker">
                        <user-select v-model="user" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Certificaatcategorie">
                        <certificate-category-select v-model="certificateCategory" />
                    </tabler-form-group>


                    <b-form-input placeholder="Kies een Gebruiker..." />
                    <b-form-input placeholder="test..." />
                </b-col>
            </b-row>

            <b-row>
                <b-col lg="4">
                    <tabler-form-group label="Enkele Groep">
                        <group-select v-model="group" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Persoon">
                        <person-select v-model="person" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Gebruiker">
                        <user-select v-model="user" />
                    </tabler-form-group>
                </b-col>
                <b-col lg="2">
                    <tabler-form-group label="Enkele Groep">
                        <group-select v-model="group" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Persoon">
                        <person-select v-model="person" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Gebruiker">
                        <user-select v-model="user" />
                    </tabler-form-group>
                </b-col>
                <b-col lg="6">
                    <tabler-form-group label="Enkele Groep">
                        <group-select v-model="group" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Persoon">
                        <person-select v-model="person" />
                    </tabler-form-group>

                    <tabler-form-group label="Enkele Gebruiker">
                        <user-select v-model="user" />
                    </tabler-form-group>
                </b-col>
            </b-row>





        </b-container>

        <b-container>
            <!--
            <b-row>
                <b-col v-for="styleCard in styleCards" :key="'style-card-'+styleCard.style.name" lg="4">
                    <show-style-card :subject-style="styleCard.style" :collapsed.sync="styleCard.collapsed" />
                </b-col>
            </b-row>
            -->

        </b-container>
    </div>


</template>

<script>
    import ALL_STYLES from "../../constants/styles";
    import ShowStyleCard from "../displays/ShowStyleCard";
    import DatePickerInput from "../inputs/DatePickerInput";
    import UserSelect from "../inputs/select/UserSelect";
    import PersonSelect from "../inputs/select/PersonSelect";
    import GroupSelect from "../inputs/select/GroupSelect";
    import TablerFormGroup from "./forms/TablerFormGroup";
    import TablerDimmer from "./cards/TablerDimmer";
    import GroupCategorySelect from "../inputs/select/GroupCategorySelect";
    import CertificateCategorySelect from "../inputs/select/CertificateCategorySelect";
    export default {
        components: {
            CertificateCategorySelect,
            GroupCategorySelect,
            TablerDimmer,
            TablerFormGroup,
            GroupSelect,
            PersonSelect,
            UserSelect,
            DatePickerInput,
            ShowStyleCard,
        },
        name: "the-page-home",
        data:function() {
            return {
                users:null,
                user:null,
                persons:null,
                person:null,
                groups:null,
                group:null,
                groupCategories:null,
                groupCategory:null,
                certificateCategories:null,
                certificateCategory:null,
                styleCards: ALL_STYLES.map(el => {
                    return {
                        style: el,
                        collapsed: false
                    };
                }),
            }
        },
    }
</script>

<style scoped>
</style>