<template>
    <div>
        <h4>
            Chat with {{ contact }}
        </h4>
        <hr>
        <div ref="scroller" :style="{ height: divHeight + 'px', overflow: 'scroll' }">
            <div v-for="message in stack">
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

        <hr>
        <div class="sticky-bottom">
            <div class="form-group">
                <textarea v-model="message.text" class="form-control" placeholder="Enter a message..." rows="3" :disabled="sending" autofocus></textarea>
            </div>
            <button class="btn btn-block btn-success" :disabled="sending || message.text.length === 0" @click="send()">
                {{ sending ? 'Sending...' : 'Send' }}
            </button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            number: { type: String, required: true },
            messages: { required: false },
            name: { type: String, required: false }
        },

        data() {
            return {
                stack: [],
                sending: false,
                message: {
                    to: this.number,
                    text: '',
                    isInbound: false,
                },
                divHeight: 0,
                windowHeight: 0,
                factor: 0.45,
            }
        },

        computed: {
            contact() {
                if(this.name) {
                    return this.name + ' (' + this.number + ')';
                }
                return this.number;
            }
        },

        watch: {
            windowHeight(val) {
                this.divHeight = this.factor * val;
            },

            stack(val) {
                this.scroll();
            }
        },

        updated() {
            this.scroll();
        },

        created() {
            if(this.messages) {
                for(let message of this.messages) {
                    this.stack.push(message);
                }
            }

            this.divHeight = this.factor * window.innerHeight;
        },

        mounted() {
            let channel = pusherInstance.subscribe('messenger.' + this.number);
            channel.bind('inbound.message', payload => {
                console.log(payload);
                this.stack.push(payload.sms);
            });

            this.$nextTick(() => {
                window.addEventListener('resize', () => {
                    this.windowHeight = window.innerHeight
                });
            });

            this.scroll();
        },

        methods: {
            send() {
                this.sending = true;
                axios.post('/api/nexmo/sms/outbound', this.message).then(response => {
                    this.stack.push(this.message);
                    this.message = {
                        to: this.number,
                        text: '',
                        isInbound: false,
                    };
                    this.sending = false;
                });
            },

            scroll() {
                this.$refs.scroller.scrollTop = this.$refs.scroller.scrollHeight;
            }
        },
    }
</script>
