<template>
    <div>
        <ivr-menu @steps="openSteps" v-if="selectedMenu.slug === undefined"></ivr-menu>
        <ivr-builder :ivr="selectedMenu" v-if="selectedMenu.slug !== undefined" @back="selectedMenu = {}"></ivr-builder>
    </div>
</template>

<script>
    import IvrMenu from "./IvrMenu";
    import IvrBuilder from "./IvrBuilder";

    export default {
        components: {IvrMenu, IvrBuilder},

        data() {
            return {
                selectedMenu: {},
            }
        },

        methods: {
            openSteps(ivr) {
                this.selectedMenu = ivr;
            },
        },
    }
</script>