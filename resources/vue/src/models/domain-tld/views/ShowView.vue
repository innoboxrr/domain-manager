<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-tld="domainTld" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-tld="domainTld" />
	    				
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

	import { showModel } from '@domainModels/domain-tld'
	import ModelCard from '@domainModels/domain-tld/widgets/ModelCard.vue'
	import ModelProfile from '@domainModels/domain-tld/widgets/ModelProfile.vue'

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

				domainTldId: this.$route.params.id,

				domainTld: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainTld');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainTld') {

					return [
						{ text: 'DomainTlds', path: '/admin/domain-tld'},
						{ text: this.domain-tld.name ?? 'DomainTld', path: '/admin/domain-tld/' + this.domain-tld.id}
					];

				} else if(this.$route.name == 'AdminEditDomainTld') {

					return [
						{ text: 'DomainTlds', path: '/admin/domain-tld'},
						{ text: this.domain-tld.name ?? 'DomainTld' , path: '/admin/domain-tld/' + this.domain-tld.id},
						{ text: 'Editar domain-tld', path: '/admin/domain-tld/' + this.domain-tld.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainTld()

				this.dataLoaded = true;
				
				this.title = this.domainTld.name;

				document.title = this.title;

			},

			async fetchDomainTld() {

				let res = await showModel(this.domainTldId);

				this.domainTld = res;

            },

		}

	}

</script>