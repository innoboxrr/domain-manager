<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-contact="domainContact" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-contact="domainContact" />
	    				
	    			</div>

	    			<div v-else>
	    				
	    				<router-view @updateData="fetchData"></router-view>

	    			</div>

	    		</div>

	    	</div>

	    </div>

	</div>

</template>

<script>

	import { showModel } from '@models/domain-contact'
	import ModelCard from '@models/domain-contact/widgets/ModelCard.vue'
	import ModelProfile from '@models/domain-contact/widgets/ModelProfile.vue'

	export default {

		components: {

			ModelCard,

			ModelProfile

		},

		mounted() {

			this.fetchData();

		},

		data() {
		
			return {

				dataLoaded: false,

				title: undefined,

				domainContactId: this.$route.params.id,

				domainContact: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainContact');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainContact') {

					return [
						{ text: 'DomainContacts', path: '/admin/domain-contact'},
						{ text: this.domain-contact.name ?? 'DomainContact', path: '/admin/domain-contact/' + this.domain-contact.id}
					];

				} else if(this.$route.name == 'AdminEditDomainContact') {

					return [
						{ text: 'DomainContacts', path: '/admin/domain-contact'},
						{ text: this.domain-contact.name ?? 'DomainContact' , path: '/admin/domain-contact/' + this.domain-contact.id},
						{ text: 'Editar domain-contact', path: '/admin/domain-contact/' + this.domain-contact.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainContact()

				this.dataLoaded = true;
				
				this.title = this.domainContact.name;

				document.title = this.title;

			},

			async fetchDomainContact() {

				let res = await showModel(this.domainContactId);

				this.domainContact = res;

            },

		}

	}

</script>