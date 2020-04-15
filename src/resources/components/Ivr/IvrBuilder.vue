<template>
    <div>
        <div class="card">
            <div class="card-header">
                {{ ivrModel.name }} ({{ ivrModel.slug }})
            </div>
            <div class="card-body text-center text-muted" v-if="loading || steps.length === 0">
                {{ loading ? 'Loading...' : 'No steps found.' }}
                <div v-if="steps.length === 0">
                    <hr>
                    <button class="btn btn-block btn-outline-secondary" @click="openEditor()" :disabled="saving">
                        Add New Step
                    </button>
                </div>
            </div>

            <table class="table" v-if="!loading && steps.length > 0">
                <thead>
                <tr>
                    <th>Action</th>
                    <th>Options</th>
                    <th>Order</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="step in steps">
                    <td>{{ step.action }}</td>
                    <td><pre>{{ step.prettyOptions }}</pre></td>
                    <td>{{ step.order }}</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <button class="btn btn-outline-secondary" @click="openEditor(step)" :disabled="stepEditor">Edit</button>
                            <button class="btn btn-outline-danger" :disabled="stepEditor" @click="deleteStep(step)">Delete</button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!stepEditor">
                    <td colspan="4" class="text-center">
                        <button class="btn btn-block btn-outline-secondary" @click="openEditor()" :disabled="saving">
                            Add New Step
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="stepEditor" class="card-body">
                <hr>
                <h4 class="text-center">Step Editor</h4>
                <div class="form-group">
                    <label>Action</label>
                    <select class="form-control" v-model="editorStep.action" @change="updateOptions()" name="action" :disabled="saving">
                        <option v-for="action in actions" :value="action" :selected="editorStep.action === action">{{ action }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Options</label>
                    <v-jsoneditor v-model="editorStep.options" :plus="false" height="300px" :disabled="saving"></v-jsoneditor>
                </div>

                <div class="form-group">
                    <label>Order</label>
                    <input type="number" class="form-control" v-model="editorStep.order" :disabled="saving">
                </div>

                <div class="btn-group btn-block">
                    <button class="btn btn-outline-danger" @click="resetEditor()" :disabled="saving">Cancel</button>
                    <button class="btn btn-outline-success" @click="saveEditor()" :disabled="saving">{{ saving ? 'Saving...' : 'Save' }}</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            ivr: {required: true}
        },

        data() {
            return {
                ivrModel: {},
                steps: [],
                saving: false,
                loading: false,
                stepEditor: false,
                editorStep: {},

                actions: [
                    'record',
                    'conversation',
                    'connect',
                    'talk',
                    'stream',
                    'input',
                    'notify',
                ],

                defaultOptions: {
                    record: {},
                    conversation: {
                        name: '',
                    },
                    connect: {
                        endpoint: [
                            {
                                type: '',
                            },
                        ],
                    },
                    talk: {
                        text: '',
                    },
                    stream: {
                        url: '',
                    },
                    input: {},
                    notify: {
                        payload: [],
                    },
                }
            }
        },

        created() {
            this.ivrModel = this.ivr;
            this.steps = this.ivr.ivr_steps;
        },

        computed: {
            baseUri() {
                return '/api/nexmo/ivr/' + this.ivr.slug + '/ivrStep';
            },

            saveUri() {
                if(this.editorStep.id !== undefined) {
                    return this.baseUri + '/' + this.editorStep.id;
                }
                return this.baseUri;
            },

            editorMethod() {
                return this.editorStep.id !== undefined ? 'put' : 'post';
            },
        },

        methods: {
            openEditor(step = {action: 'talk', options: this.defaultOptions.talk, order: 100}) {
                this.editorStep = step;
                this.stepEditor = true;
            },

            updateOptions() {
                this.editorStep.options = this.defaultOptions[this.editorStep.action];
            },

            resetEditor() {
                this.openEditor();
                this.stepEditor = false;
            },

            saveEditor() {
                this.saving = true;
                axios({
                    method: this.editorMethod,
                    url: this.saveUri,
                    data: {
                        action: this.editorStep.action,
                        order: this.editorStep.order,
                        options: JSON.stringify(this.editorStep.options)
                    },
                }).then(response => {
                    this.saving = false;
                    this.resetEditor();
                    this.fetchSteps();
                });
            },

            fetchSteps() {
                this.loading = true;
                axios.get(this.baseUri).then(response => {
                    this.loading = false;
                    this.steps = response.data;
                });
            },

            deleteStep(step) {
                axios.delete(this.baseUri + '/' + step.id).then(response => {
                    this.fetchSteps();
                });
            },
        },
    }
</script>
