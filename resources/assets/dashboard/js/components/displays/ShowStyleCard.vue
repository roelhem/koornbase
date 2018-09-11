<template>
    <tabler-card no-body collapsible collapsible-with-header
                 :status="subjectStyle.color"
                 :icon="subjectStyle.icon"
                 :collapsed.sync="collapsedValue"
                 :title="subjectStyle.label">


        <detail-view in-card>

            <detail-entry label="Naam">
                <code>{{ subjectStyle.name }}</code>
            </detail-entry>

            <detail-entry v-if="subjectStyle.color" label="Kleur">
                <div class="h-5 w-5 rounded" :class="'bg-'+subjectStyle.color"
                     v-b-tooltip.hover.html="`De kleur <em>${subjectStyle.color}</em> uit bootstrap.`">
                </div>
            </detail-entry>


            <detail-entry v-if="subjectStyle.icon" label="Icons">
                <base-icon v-for="(icon, iconSet) in subjectStyle.icon"
                           :key="'icon-from-'+iconSet"
                           :icon="icon"
                           :from="iconSet"
                           class="text-muted mr-2"
                           v-b-tooltip.hover.html="`Uit de <em>${iconSet}</em> icon-set.`"
                />
            </detail-entry>


            <detail-entry label="Avatars">
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
            </detail-entry>

            <detail-entry label="Tags">
                <div class="tags">
                    <base-tag :default-style="subjectStyle"
                              label="leeg"
                    />
                    <base-tag :default-style="subjectStyle"
                              :avatar="{letters:'cD'}"
                              label="Met letters"
                    />
                </div>
            </detail-entry>

        </detail-view>

    </tabler-card>
</template>

<script>
    import TablerCard from "../layouts/cards/TablerCard";
    import BaseIcon from "./BaseIcon";
    import BaseTag from "./BaseTag";
    import BaseAvatar from "./BaseAvatar";
    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";

    export default {
        components: {
            DetailEntry,
            DetailView,
            TablerCard, BaseIcon, BaseTag, BaseAvatar},
        name: "show-style-card",

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
