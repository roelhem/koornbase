<template>
    <div>

        <!-- start FIRST INPUT ROW -->
        <b-form-row>


            <!-- start NAME_FIRST COLUMN -->
            <b-col lg="4">
                <tabler-form-group label="Voornaam"
                                   :label-for="getId('firstName')"
                                   required
                >
                    <b-form-input :id="getId('firstName')"
                                  v-model="firstName"
                                  :size="size"
                                  placeholder="Voornaam/roepnaam..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_FIRST COLUMN -->



            <!-- start NAME_MIDDLE COLUMN -->
            <b-col lg="8">
                <tabler-form-group label="Overige namen"
                                   :label-for="getId('middleName')"
                >
                    <b-form-input :id="getId('middleName')"
                                  v-model="middleName"
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
                                   :label-for="getId('initials')"
                >
                    <b-form-input :id="getId('initials')"
                                  v-model="initials"
                                  :size="size"
                                  placeholder="Voorletters/initialen..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_INITIALS COLUMN -->



            <!-- start NAME_PREFIX COLUMN -->
            <b-col lg="2">
                <tabler-form-group label="Tussenvoegsel"
                                   :label-for="getId('prefixName')"
                >
                    <b-form-input :id="getId('prefixName')"
                                  v-model="prefixName"
                                  :size="size"
                                  placeholder="de, van der, ..."
                    ></b-form-input>
                </tabler-form-group>
            </b-col>
            <!-- end NAME_PREFIX COLUMN -->


            <!-- start NAME_LAST COLUMN -->
            <b-col lg="7">
                <tabler-form-group label="Achternaam"
                                   :label-for="getId('lastName')"
                                   required
                >
                    <b-form-input :id="getId('lastName')"
                                  v-model="lastName"
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
                        firstName:null,
                        middleName:null,
                        prefixName:null,
                        lastName:null,
                        initials:null,
                    };
                }
            },

            size:String,
        },


        computed:{
            firstName: {
                get() { return this.getValue('firstName'); },
                set(newValue) { this.setValue('firstName', newValue); },
            },

            middleName: {
                get() { return this.getValue('middleName'); },
                set(newValue) { this.setValue('middleName', newValue); },
            },

            prefixName: {
                get() { return this.getValue('prefixName'); },
                set(newValue) { this.setValue('prefixName', newValue); },
            },

            lastName: {
                get() { return this.getValue('lastName'); },
                set(newValue) { this.setValue('lastName', newValue); },
            },

            initials: {
                get() { return this.getValue('initials'); },
                set(newValue) { this.setValue('initials', newValue); },
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