<template>

    <div>

        <b-container class="mb-8">

            <tabler-form-group title="Groep">
                <group-select v-model="group" multiple />
            </tabler-form-group>

            <tabler-form-group title="Person">
                <person-select v-model="person" multiple />
            </tabler-form-group>

            <tabler-form-group title="User">
                <user-select v-model="user" multiple />
            </tabler-form-group>






            <pre>
                {{ group }}
                {{ person }}
                {{ user }}
            </pre>

        </b-container>

        <b-container>

            <b-row>

                <b-col v-for="styleCard in styleCards" :key="'style-card-'+styleCard.style.name" lg="4">

                    <show-style-card :subject-style="styleCard.style" :collapsed.sync="styleCard.collapsed" />

                </b-col>

            </b-row>

        </b-container>
    </div>


</template>

<script>

    import ALL_STYLES from "../constants/styles";
    import ShowStyleCard from "./ShowStyleCard";
    import DatePickerInput from "./DatePickerInput";
    import UserSelect from "./forms/select/UserSelect";
    import PersonSelect from "./forms/select/PersonSelect";
    import GroupSelect from "./forms/select/GroupSelect";
    import TablerFormGroup from "./TablerFormGroup";

    export default {
        components: {
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
                user:["4"],
                person:null,
                group:null,
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