<template>

    <form-layout v-bind="formLayoutProps"
                 v-on="formLayoutListeners"
    >

        <form-simple-input :id="getFieldId('name')"
                           label="Groepsnaam"
                           placeholder="Naam voor de nieuwe groep."
                           required
                           v-model.trim="$v.values.name.$model"
                           :validation="$v.values.name"
                           horizontal="lg"
        />

        <form-simple-input :id="getFieldId('name_short')"
                           label="Korte naam"
                           placeholder="Een kortere, maar duidelijke naam."
                           v-model.trim="$v.values.name_short.$model"
                           :validation="$v.values.name_short"
                           horizontal="lg"
        />

        <form-simple-input :id="getFieldId('member_name')"
                           label="Titel voor groepsleden"
                           placeholder="Hoe noem je iemand die in deze nieuwe groep zit?"
                           v-model.trim="$v.values.member_name.$model"
                           :validation="$v.values.member_name"
                           horizontal="lg"
        />

        <form-textarea-input :id="getFieldId('description')"
                             label="Omschrijving"
                             placeholder="Korte uitleg van de functie van de nieuwe groep."
                             v-model.trim="$v.values.description.$model"
                             :validation="$v.values.description"
        />

        <tabler-form-group label="Categorie"
                           :label-for="getFieldId('category')"
                           required
                           :validation="$v.values.category"
        >
            <group-category-select :id="getFieldId('category')"
                                   v-model="$v.values.category.$model"
                                   :allowEmpty="false"
            />
        </tabler-form-group>

    </form-layout>

</template>

<script>
    import gql from "graphql-tag";
    import FormLayout from "../../layouts/forms/FormLayout";
    import controlForm from "../../../mixins/controlForm";
    import { validationMixin } from 'vuelidate';
    import { required, maxLength } from 'vuelidate/lib/validators';
    import FormSimpleInput from "../../inputs/FormSimpleInput";
    import FormTextareaInput from "../../inputs/FormTextareaInput";
    import TablerFormGroup from "../../layouts/forms/TablerFormGroup";
    import GroupCategorySelect from "../../inputs/select/GroupCategorySelect";

    export default {
        components: {
            GroupCategorySelect,
            TablerFormGroup,
            FormTextareaInput,
            FormSimpleInput,
            FormLayout},
        name: "create-group-form",

        form: {
            title:"Nieuwe Groep",
            actionType:"create",
            values() {
                return {
                    name:null,
                    name_short:null,
                    member_name:null,
                    description:null,
                    category:null
                }
            }
        },

        mixins:[controlForm, validationMixin],

        validations() {
            return {
                values: {
                    name: {required, maxLength:maxLength(255) },
                    name_short: { maxLength:maxLength(63) },
                    member_name: { maxLength:maxLength(255) },
                    description: {},
                    category: { required }
                }
            }
        },

        methods: {
            submit() {

                this.$v.values.$touch();

                if(this.$v.values.$invalid) {
                    this.addMessage("Sommige velden hebben ongeldige waarden. Pas deze waarden aan en probeer het opnieuw.");
                } else {

                    const variables = {
                        category_id:this.values.category.id,
                        name:this.values.name,
                        name_short:this.values.name_short,
                        member_name:this.values.member_name,
                        description:this.values.description
                    };


                    this.$apollo.mutate({
                        mutation: gql`
                            mutation createGroup($category_id:ID!, $name:String!, $name_short:String, $member_name:String, $description:String) {
                                createGroup(category_id:$category_id, name:$name, name_short:$name_short, member_name:$member_name, description:$description) {
                                    id
                                }
                            }
                        `,
                        variables,
                    }).then(data => {
                        console.log(data);
                        this.hide();
                        this.$router.push({ name: 'db.groups.view', params:{id: data.data.createGroup.id} });
                    }).catch(error => {
                        this.addMessage(error.message);
                    });
                }

            }
        }
    }
</script>

<style scoped>

</style>