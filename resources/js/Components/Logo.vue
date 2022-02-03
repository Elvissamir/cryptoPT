<template>
   <Link :href="route('portfolioCryptos.show')" as="button" class="logo">CPT</Link>
</template>

<script>

import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link,
    },
    setup() {
        
    },
}
</script>
