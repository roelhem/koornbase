<template>
    <tabler-card no-body collapsible collapsible-with-header
                 :status="subjectStyle.color"
                 :icon="subjectStyle.icon"
                 :collapsed.sync="collapsedValue">
        <template slot="title">
            {{ subjectStyle.label }}
            <span class="small text-muted-dark">
                <code>{{ subjectStyle.name }}</code>
            </span>
        </template>

        <table class="table card-table table-sm">
            <tbody>
                <tr v-if="subjectStyle.color">
                    <th>Kleur</th>
                    <td>
                        <div class="h-4 rounded"
                             :class="'bg-'+subjectStyle.color"
                             v-b-tooltip.hover.html="`De kleur <em>${subjectStyle.color}</em> uit bootstrap.`">
                        </div>
                    </td>
                </tr>
                <tr v-if="subjectStyle.icon">
                    <th>Icons</th>
                    <td>
                        <base-icon v-for="(icon, iconSet) in subjectStyle.icon"
                                   :key="'icon-from-'+iconSet"
                                   :icon="icon"
                                   :from="iconSet"
                                   class="text-muted mr-2"
                                   v-b-tooltip.hover.html="`Uit de <em>${iconSet}</em> icon set.`"
                        />
                    </td>
                </tr>
                <tr>
                    <th>Avatar</th>
                    <td>
                        <div class="avatar-list">
                            <base-avatar :default-style="subjectStyle"
                                         v-b-tooltip.hover="'Een lege avatar.'"
                            />
                            <base-avatar :default-style="subjectStyle"
                                         v-b-tooltip.hover="'Een avatar met placeholder letters.'"
                                         letters="Ab"
                            />
                            <base-avatar :default-style="subjectStyle"
                                         v-b-tooltip.hover="'Een avatar met afbeelding.'"
                                         image="https://www.w3schools.com/howto/img_avatar.png"
                            />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Tag</th>
                    <td>
                        <div class="tags">
                            <base-tag :default-style="subjectStyle"
                                      label="leeg"
                            />
                            <base-tag :default-style="subjectStyle"
                                      :avatar="{letters:'cD'}"
                                      label="Met letters"
                            />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>


    </tabler-card>
</template>

<script>
    import TablerCard from "./TablerCard";
    import BaseIcon from "./BaseIcon";
    import BaseTag from "./BaseTag";
    import BaseAvatar from "./BaseAvatar";

    export default {
        components: {TablerCard, BaseIcon, BaseTag, BaseAvatar},
        name: "card-show-style",

        props: {

            subjectStyle:{
                type:Object,
                required:true,
            },

            collapsed: {
                type:Boolean,
                default:false,
            }

        },

        computed: {
            collapsedValue:{
                get:function() {
                    return this.collapsed;
                },
                set:function(newValue) {
                    this.$emit('update:collapsed',newValue);
                }
            }
        }
    }
</script>

<style scoped>

</style>
