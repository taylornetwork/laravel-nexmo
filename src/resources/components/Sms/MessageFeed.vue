<template>
    <div ref="scroller" :style="{ height: divHeight + 'px', overflow: 'scroll' }">
        <div v-for="message in messageList">
            <div v-if="message.isInbound" class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        {{ message.text }}
                    </div>
                </div>
            </div>
            <div v-else class="col-md-6 offset-md-6">
                <div class="card bg-primary text-light">
                    <div class="card-body">
                        {{ message.text }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: [
            'contact',
        ],

        data() {
            return {
                messages: [],
            }
        },

        watch: {
            contact(newValue, oldValue) {
                this.messages = [];
                if(newValue !== null && newValue.messages) {
                    this.messages = newValue.messages;
                }
            }
        }

        // @todo divHeight
    }
</script>

