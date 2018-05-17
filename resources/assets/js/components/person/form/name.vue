<template>

    <fieldset>

        <legend>Naam</legend>

        <div class="row">

            <div class="col-lg-5">
                <f-simple-input label="Voornaam" name="name[first]" :required="true" v-model="first"
                                :input-events="firstEventHandler"></f-simple-input>
            </div>

            <div class="col-lg-7">
                <f-simple-input ref="middle" label="Overige Voornamen" name="name[middle]" v-model="middle"></f-simple-input>
            </div>

        </div>


        <div class="row">

            <div class="col-lg-3">
                <f-simple-input label="Initialen" name="name[initials]" v-model="initials" :events="initialsEventHandler" :required="true" />
            </div>

            <div class="col-lg-2">
                <f-simple-input label="Tussenvoegsel" name="name[prefix]" v-model="prefix"></f-simple-input>
            </div>

            <div class="col-lg-7">
                <f-simple-input label="Achternaam" name="name[last]" :required="true" v-model="last"></f-simple-input>
            </div>

        </div>

        <f-simple-input label="Koornbeurs Bijnaam" name="name[nickname]" v-model="nickname" :placeholder="first"></f-simple-input>

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