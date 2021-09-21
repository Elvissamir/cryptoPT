<template>
    <Link class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-black md:text-base" :href="cryptosUrl">
        Add 
        <font-awesome-icon :icon="['fas', 'plus']" />
    </Link>
</template>

<script>

import { Link } from '@inertiajs/inertia-vue3'
export default {
    components: {
        Link
    },
    setup() {
        
        const cryptosUrl = '/cryptos';

        return {
            cryptosUrl,
        }
    },
}
</script>