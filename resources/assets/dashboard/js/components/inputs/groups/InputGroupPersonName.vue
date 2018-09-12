<template>
    <div>

        <!-- start FIRST INPUT ROW -->
        <b-form-row>


            <!-- start NAME_FIRST COLUMN -->
            <b-col lg="4">
                <tabler-form-group label="Voornaam"
                                   :label-for="getId('name_first')"
                                   required
                >
                    <b-form-input :id="getId('name_first')"
                                  v-model="name_first"
                                  :size="size"
                                  placeholder="Voornaam/roepnaam..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_FIRST COLUMN -->



            <!-- start NAME_MIDDLE COLUMN -->
            <b-col lg="8">
                <tabler-form-group label="Overige namen"
                                   :label-for="getId('name_middle')"
                >
                    <b-form-input :id="getId('name_middle')"
                                  v-model="name_middle"
                                  :size="size"
                                  placeholder="Overige voornamen, tweede naam, ..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_MIDDLE COLUMN -->



        </b-form-row>
        <!-- end FIRST INPUT ROW -->

        <!-- start SECOND INPUT ROW -->
        <b-form-row>


            <!-- start NAME_INITIALS COLUMN -->
            <b-col lg="3">
                <tabler-form-group label="Voorletters"
                                   :label-for="getId('name_initials')"
                >
                    <b-form-input :id="getId('name_initials')"
                                  v-model="name_initials"
                                  :size="size"
                                  placeholder="Voorletters/initialen..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_INITIALS COLUMN -->



            <!-- start NAME_PREFIX COLUMN -->
            <b-col lg="2">
                <tabler-form-group label="Tussenvoegsel"
                                   :label-for="getId('name_prefix')"
                >
                    <b-form-input :id="getId('name_prefix')"
                                  v-model="name_prefix"
                                  :size="size"
                                  placeholder="de, van der, ..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_PREFIX COLUMN -->


            <!-- start NAME_LAST COLUMN -->
            <b-col lg="7">
                <tabler-form-group label="Achternaam"
                                   :label-for="getId('name_last')"
                                   required
                >
                    <b-form-input :id="getId('name_last')"
                                  v-model="name_last"
                                  :size="size"
                                  placeholder="Achternaam/familienaam..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_LAST COLUMN -->



        </b-form-row>
        <!-- end SECOND INPUT ROW -->


    </div>
</template>

<script>

    import TablerFormGroup from "../../layouts/forms/TablerFormGroup";

    export default {
        name: "input-group-person-name",

        components:{
            TablerFormGroup
        },

        model: {
            prop:'value',
            event:'input'
        },


        props:{
            id:{
                type:String,
                required:true,
            },

            value:{
                type:Object,
                default() {
                    return {
                        name_first:null,
                        name_middle:null,
                        name_prefix:null,
                        name_last:null,
                        name_initials:null,
                    };
                }
            },

            size:String,
        },


        computed:{
            name_first: {
                get() { return this.getValue('name_first'); },
                set(newValue) { this.setValue('name_first', newValue); },
            },

            name_middle: {
                get() { return this.getValue('name_middle'); },
                set(newValue) { this.setValue('name_middle', newValue); },
            },

            name_prefix: {
                get() { return this.getValue('name_prefix'); },
                set(newValue) { this.setValue('name_prefix', newValue); },
            },

            name_last: {
                get() { return this.getValue('name_last'); },
                set(newValue) { this.setValue('name_last', newValue); },
            },

            name_initials: {
                get() { return this.getValue('name_initials'); },
                set(newValue) { this.setValue('name_initials', newValue); },
            },
        },


        methods:{

            getId(fieldName) {
                return this.id+'__'+fieldName;
            },

            getValue(fieldName) {
                return this.value[fieldName];
            },

            setValue(fieldName, newValue) {

                const res = Object.assign({}, this.value);
                res[fieldName] = newValue || null;

                this.$emit('input', res, fieldName, newValue);
            }

        }
    }
</script>

<style scoped>

</style>