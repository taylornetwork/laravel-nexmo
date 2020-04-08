<template>
    <div>
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
                <input type="text" v-model="message.text" class="form-control" placeholder="Enter a message..." :disabled="sending" autofocus>
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
            messages: { type: Array, default: []},
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
                factor: 0.55,
            }
        },

        watch: {
            windowHeight(val) {
                this.divHeight = this.factor * val;
            },
        },

        updated() {
            this.$refs.scroller.scrollTop = this.$refs.scroller.scrollHeight;
        },

        created() {
            for(let message of this.messages) {
                this.stack.push(message);
            }

            this.divHeight = this.factor * window.innerHeight;
        },

        mounted() {
            let self = this;

            this.$echo.channel('messenger.inbound.' + this.number).listen('InboundMessageReceived', payload => {
                console.log(payload);
                this.stack.push(payload.sms);
            });

            this.$nextTick(function () {
                window.addEventListener('resize', function(e) {
                    self.windowHeight = window.innerHeight
                });
            })

            this.$refs.scroller.scrollTop = this.$refs.scroller.scrollHeight;
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
            }
        },
    }
</script>
