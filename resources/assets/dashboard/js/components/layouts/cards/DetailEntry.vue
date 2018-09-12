 <template>
    <tr>
        <template v-if="!flipped">
            <th :title="title">
                <slot name="label">
                    <base-icon v-if="useIcon"
                               :icon="icon"
                               :from="iconFrom"
                               class="text-muted" />
                    {{ label }}
                </slot>
            </th>
            <slot name="td">
                <td colspan="2"><slot></slot></td>
            </slot>
        </template>
        <template v-else>
            <slot name="flipside">
                <td colspan="3" class="bg-blue-lightest">
                    <slot name="back" />
                </td>
            </slot>
        </template>
    </tr>
</template>

<script>
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        components: {BaseIcon},
        name: "detail-entry",

        props: {

            label:String,

            icon:{
                type:[String,Object],
                default:null,
            },

            iconFrom:{
                type:[String,Array],
                default:function() {
                    return ['fa','fe']
                }
            },

            title:null,

            flipped:{
                type:Boolean,
                default:false
            }

        },

        computed: {
            useIcon:function() {
                return !!this.icon;
            }
        }
    }
</script>

<style scoped>

</style>