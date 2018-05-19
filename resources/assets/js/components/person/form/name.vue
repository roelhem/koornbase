<template>

    <fieldset>

        <b-form-row>

            <b-col lg="5">
                <b-form-group label="Voornaam"
                              label-for="person_name_first">
                    <b-form-input id="person_name_first"
                                  name="name[first]"
                                  :required="true"
                                  v-model.trim="first">
                    </b-form-input>
                </b-form-group>
            </b-col>

            <b-col>
                <b-form-group label="Overige Voornamen"
                              label-for="person_name_middle">
                    <b-form-input id="person_name_middle"
                                  name="name[middle]"
                                  v-model.trim="middle">
                    </b-form-input>
                </b-form-group>
            </b-col>

        </b-form-row>


        <b-form-row>

            <b-col lg="3">
                <b-form-group label="Initialen"
                              label-for="person_name_initials">
                    <b-form-input id="person_name_initials"
                                  name="name[initials]"
                                  v-model="initials">
                    </b-form-input>
                </b-form-group>
            </b-col>

            <b-col lg="2">
                <b-form-group label="Tussenvoegsel"
                              label-for="person_name_prefix">
                    <b-form-input id="person_name_prefix"
                                  name="name[prefix]"
                                  v-model="prefix">
                    </b-form-input>
                </b-form-group>
            </b-col>

            <b-col>
                <b-form-group label="Achternaam"
                              label-for="person_name_last">
                    <b-form-input id="person_name_last"
                                  name="name[last]"
                                  v-model="last"
                                  :required="true">
                    </b-form-input>
                </b-form-group>
            </b-col>

        </b-form-row>

        <b-form-row>
            <b-col>
                <b-form-group label="Bijnaam op de Koornbeurs"
                              label-for="person_name_nickname">
                    <b-form-input id="person_name_nickname"
                                  name="name[nickname]"
                                  v-model="nickname"
                                  :placeholder="first">
                    </b-form-input>
                </b-form-group>
            </b-col>
        </b-form-row>

    </fieldset>

</template>

<script>
    export default {
        props: {
            value:{
                type:Object,
                default:function() {
                    return {
                        initials:null,
                        first:null,
                        middle:null,
                        prefix:null,
                        last:null,
                        nickname:null
                    }
                }
            }
        },

        watch: {
            initialsDefaultValue: function(newVal, oldVal) {
                if(!this.initials || this.initials === oldVal) {
                    this.initials = newVal
                }
            }
        },

        methods: {
            formatInitials(letters) {
                let res = '';

                if(letters) {
                    for (let i = 0; i < letters.length; i++) {
                        res += letters[i].toUpperCase() + '.';
                    }
                }

                return res;
            },

            changeVar(changes) {
                let res = Object.assign(this.value, changes);
                this.$emit('input', res);
            }
        },

        computed: {

            initials:{
                get: function() { return this.value.initials },
                set: function(newValue) {
                    if(!newValue) {
                        this.changeVar({'initials':null});
                    } else {
                        this.changeVar({
                            'initials':this.formatInitials(newValue.match(/\w/g))
                        });
                    }
                }
            },
            first:{
                get: function() { return this.value.first },
                set: function(newValue) { this.changeVar({'first':newValue}) }
            },
            middle:{
                get: function() { return this.value.middle },
                set: function(newValue) { this.changeVar({'middle':newValue}) }
            },
            prefix:{
                get: function() { return this.value.prefix },
                set: function(newValue) { this.changeVar({'prefix':newValue}) }
            },
            last:{
                get: function() { return this.value.last },
                set: function(newValue) { this.changeVar({'last':newValue}) }
            },
            nickname:{
                get: function() { return this.value.nickname },
                set: function(newValue) { this.changeVar({'nickname':newValue}) }
            },


            initialsDefaultValue: function() {
                const first = this.first || '';
                const middle = this.middle || '';
                const name = first+' '+middle;
                const caps = name.match(/\b(\w)/g) || [];
                return this.formatInitials(caps);
            },

            initialsEventHandler: function() {
                const self = this;

                return {
                    blur() {
                        let val = self.initials || '';
                        this.initials = self.formatInitials(val.match(/\w/g));
                    }
                }
            },

            firstEventHandler: function() {
                const self = this;

                return {
                    keypress( event ) {
                        console.log(self);

                        if(event.keyCode === 32 && self.first) {
                            event.preventDefault();

                            self.$refs.middle.$refs.input.focus();
                        }
                    }
                }
            }
        },
    }
</script>

<style scoped>

</style>