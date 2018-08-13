<template>

    <b-container>
        <tabler-page-header title="Nieuwe Gebruiker Maken"
                            :breadcrumb="[
                                {icon:'users',text:'Gebruikers',to:{name:'users.index'}},
                                {text:'Nieuwe Gebruiker', active:true}
                            ]"
        />


        <tabler-card title="Nieuwe Gebruiker">


            <form>
                <form-simple-input id="create_user--name"
                                   label="Gebruikersnaam"
                                   required
                                   placeholder="De naam van de nieuwe gebruiker."
                />

                <form-simple-input id="create_user--email"
                                   label="E-mailadres"
                                   type="email"
                                   required
                                   placeholder="Het e-mailadres van de gebruiker."
                />

                <tabler-form-group label="Wachtwoord" required>

                    <fieldset class="form-fieldset">
                        <b-form-row>
                            <b-col col-lg="12">
                                <div class="custom-switches-stacked">
                                    <form-switch v-model="form.passwordInitType"
                                                 type="radio"
                                                 name="password-set-type"
                                                 value="set"
                                    >
                                        Het nieuwe wachtwoord <em>handmatig instellen</em>.<br />
                                    </form-switch>
                                    <form-switch v-model="form.passwordInitType"
                                                 type="radio"
                                                 name="password-set-type"
                                                 value="generate"
                                    >Een nieuw wachtwoord <em>genereren</em>.</form-switch>
                                    <form-switch v-model="form.passwordInitType"
                                                 type="radio"
                                                 name="password-set-type"
                                                 value="email"
                                    >De gebruiker <em>een email sturen</em> om een wachtwoord te maken.</form-switch>
                                </div>
                            </b-col>
                            <b-col>
                                <template v-if="form.passwordInitType === 'set'">
                                    <form-simple-input id="create_user--password"
                                                       label="Wachtwoord"
                                                       type="password"
                                                       placeholder="Vul hier het wachtwoord voor de nieuwe gebruiker in."
                                    />
                                    <form-simple-input id="create_user--password-repeat"
                                                       label="Wachtwoord Herhalen"
                                                       type="password"
                                                       placeholder="Herhaal hier het wachtwoord."
                                    />
                                </template>
                                <template v-else-if="form.passwordInitType === 'generate'">

                                    <tabler-form-group label="Wachtwoord-generator opties">
                                        <b-form-checkbox-group stacked id="create_user--password-options" v-model="form.passwordGenerateOptions">
                                            <b-form-checkbox value="a">Gemakkelijk in te voeren karakters.</b-form-checkbox>
                                            <b-form-checkbox value="b">Leesfouten vermijden.</b-form-checkbox>
                                            <b-form-checkbox value="c">Alleen Hoofdletters en cijfers.</b-form-checkbox>
                                        </b-form-checkbox-group>
                                    </tabler-form-group>

                                    <form-range-input label="Lengte Wachtwoord"
                                                      id="create_user--password-size"
                                                      :max="100" :min="10" :step="1"
                                                      v-model="form.passwordGenerateLength"
                                    />

                                    <tabler-form-group label="Wachtwoord Feedback">
                                        <b-form-radio-group stacked id="create_user--password-generated-feedback" v-model="form.passwordGenerateFeedback">
                                            <b-form-radio value="show">Eenmalig tonen na het aanmaken.</b-form-radio>
                                            <b-form-radio value="email">Per mail versturen naar de gebruiker.</b-form-radio>
                                            <b-form-radio value="email-to">
                                                E-mail versturen naar:<br />
                                                <b-form-input size="sm" type="email" :disabled="form.passwordGenerateFeedback !== 'email-to'"></b-form-input>.
                                            </b-form-radio>
                                        </b-form-radio-group>
                                    </tabler-form-group>
                                </template>
                                <template v-else-if="form.passwordInitType === 'email'">
                                    <tabler-form-group label="E-mail versturen naar">
                                        <b-form-radio-group stacked id="create_user--password-email-to">
                                            <b-form-radio value="user">Het E-mailadres van de gebruiker.</b-form-radio>
                                            <b-form-radio value="other">
                                                Ander E-mailadres:<br />
                                                <b-form-input size="sm" type="email" disabled></b-form-input>.
                                            </b-form-radio>
                                        </b-form-radio-group>
                                    </tabler-form-group>

                                    <form-simple-input id="create_user--password-email-valid-till"
                                                       label="Link in de e-mail geldig tot"
                                    />
                                </template>
                            </b-col>
                        </b-form-row>
                    </fieldset>
                </tabler-form-group>

                <tabler-form-group id="create_user--person" label="Persoon aan account koppelen.">
                    <person-select v-model="person" />
                </tabler-form-group>
            </form>


            <template slot="footer">
                <div class="d-flex text-right">
                    <b-button variant="link" @click="$router.back()">Annuleren</b-button>
                    <b-button variant="primary" class="ml-auto">Aanmaken</b-button>
                </div>
            </template>
        </tabler-card>

    </b-container>

</template>

<script>
    import TablerPageHeader from "../../TablerPageHeader";
    import TablerCard from "../../TablerCard";
    import FormSimpleInput from "../../forms/FormSimpleInput";
    import FormSwitch from "../../FormSwitch";
    import TablerFormGroup from "../../TablerFormGroup";
    import FormRangeInput from "../../forms/FormRangeInput";
    import FormColorInput from "../../forms/FormColorInput";
    import PersonSelect from "../../forms/select/PersonSelect";

    export default {
        components: {
            PersonSelect,
            FormColorInput,
            FormRangeInput,
            TablerFormGroup,
            FormSwitch,
            FormSimpleInput,
            TablerCard,
            TablerPageHeader
        },

        data() {
            return {
                form: {
                    passwordInitType:'set',
                    passwordGenerateLength:20,
                    passwordGenerateOptions:['a','c'],
                    passwordGenerateFeedback:'show',
                    person:null
                }
            };
        }

    }
</script>

<style scoped>

</style>