<template>

    <div class="form-group">
        <label class="form-label">{{ label || 'Naam' }}
            <span class="form-required">*</span>
        </label>

        <div class="f-name-input-inputs">
            <input class="form-control" type="text" id="name" name="name" placeholder="Volledige naam" maxlength="255"
                   v-model="name">
            <div class="input-group">
                <span class="input-group-prepend">
                    <label class="input-group-text">
                        <input type="checkbox" v-model="short_independent"
                            @change=" short = short_independent ? short : shorten(name) ">
                    </label>
                </span>
                <input class="form-control" type="text" placeholder="Afgekorte naam" maxlength="63"
                       v-model="short" @input="short_independent = short !== ''">
                <input type="hidden" id="name_short" name="name_short" :value="real_short"/>
            </div>
            <div class="input-group">
                <span class="input-group-prepend">
                    <label class="input-group-text">
                        <input type="checkbox" v-model="slug_independent"
                               @change=" slug = slug_independent ? slug : slugify(short) ">
                    </label>
                </span>
                <input class="form-control" type="text" placeholder="URL-veilige naam" maxlength="63"
                       v-model="slug" @input="slug_independent = slug !== ''" @blur="slug = slugify(slug)">
                <input type="hidden" id="slug" name="slug" :value="real_slug"/>
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        name: "f-name-input",
        props: [
            "label",
            "value",
            'shortValue',
            'slugValue',
        ],
        data: function() {
            return {
                name: this.value,
                short_independent: this.shortValue !== undefined,
                short: this.shortValue,
                slug_independent: this.slugValue !== undefined,
                slug: this.slugValue
            }
        },
        watch: {
            name: function(val) {
                if(!this.short_independent) {
                    this.short = this.shorten(val);
                }
            },
            short: function(val) {
                if(!this.slug_independent) {
                    this.slug = this.slugify(val);
                }
            }
        },
        computed: {
            real_short: function() {
                if(this.short_independent) {
                    return this.short;
                } else {
                    return null;
                }
            },
            real_slug: function() {
                if(this.slug_independent) {
                    return this.slug;
                } else {
                    return null;
                }
            }
        },
        methods: {
            shorten(text) {
                if(text === undefined) {
                    return undefined;
                } else {
                    return text.toString().substr(0, 63);
                }
            },
            slugify(text) {
                if(text === undefined) {
                    return undefined;
                } else {
                    return text.toString().toLowerCase()
                        .replace(/\s+/g, '-')           // Replace spaces with -
                        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                        .replace(/^-+/, '')             // Trim - from start of text
                        .replace(/-+$/, '');            // Trim - from end of text
                }
            }
        }
    }
</script>

<style scoped>
    .form-control {
        border-radius: 0px;
    }
</style>