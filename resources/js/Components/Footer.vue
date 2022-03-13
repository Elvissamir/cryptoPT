<template>
    <footer class="bg-gray-800 mt-8">
                <div class="flex flex-col w-10/12 mx-auto py-8 text-white">
                    <!-- LINKS -->
                    <div class="flex flex-col mx-auto">
                        <div class=" flex w-full mb-1 justify-center">
                            <p class="font-bold">My Social Networks:</p>
                        </div>

                        <div class="flex">
                            <div class="flex items-baseline font-bold">
                                <p class="flex items-center text-sm sm:text-base">
                                    <font-awesome-icon :icon="['fas', 'envelope']" class="mr-2"/>
                                    elvissam.software@gmail.com
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between mt-2 mx-auto text-lg font-black">
                            <div class="flex">
                                <a href="https://elvisportfolio.herokuapp.com/">
                                    <font-awesome-icon :icon="['fas', 'globe']" size="lg"/>
                                </a>
                            </div>
                                
                            <div class="flex ml-3">
                                <a href="https://github.com/Elvissamir">
                                    <font-awesome-icon :icon="['fab', 'github']" size="lg" />
                                </a>
                            </div>

                            <div class="flex ml-3">
                                <a class="ml-2" href="https://linkedin.com/in/elvissam">
                                    <font-awesome-icon :icon="['fab', 'linkedin']" size="lg" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- INFO -->
                    <div class="flex flex-col mt-3 mx-auto items-center text-center">
                        <div class="flex flex-wrap justify-center font-bold">
                            <div class="flex flex-wrap items-baseline">
                                <font-awesome-icon :icon="['fas', 'cog']" size="lg" class="my-auto"/>
                                <p class="ml-1">Designed</p>
                                <p class="ml-1">&</p>
                                <p class="ml-1">Developed</p>
                            </div>
                            <p class="ml-1">by</p>
                            <p class="ml-1">Elvis Carrasco</p>
                        </div>
                        <p class="font-bold mt-2">
                            Crypto Portfolio Tracker
                        </p>
                        <p class="font-bold">
                            CPT - 2021
                        </p>
                    </div>

                </div>
            </footer>
</template>

<script>
export default {
    setup() {
        
    },
}
</script>