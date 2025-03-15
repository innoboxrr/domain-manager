<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-dns="domainDns" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-dns="domainDns" />
	    				
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

	import { showModel } from '@models/domain-dns'
	import ModelCard from '@models/domain-dns/widgets/ModelCard.vue'
	import ModelProfile from '@models/domain-dns/widgets/ModelProfile.vue'

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

				domainDnsId: this.$route.params.id,

				domainDns: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainDns');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainDns') {

					return [
						{ text: 'DomainDns', path: '/admin/domain-dns'},
						{ text: this.domain-dns.name ?? 'DomainDns', path: '/admin/domain-dns/' + this.domain-dns.id}
					];

				} else if(this.$route.name == 'AdminEditDomainDns') {

					return [
						{ text: 'DomainDns', path: '/admin/domain-dns'},
						{ text: this.domain-dns.name ?? 'DomainDns' , path: '/admin/domain-dns/' + this.domain-dns.id},
						{ text: 'Editar domain-dns', path: '/admin/domain-dns/' + this.domain-dns.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainDns()

				this.dataLoaded = true;
				
				this.title = this.domainDns.name;

				document.title = this.title;

			},

			async fetchDomainDns() {

				let res = await showModel(this.domainDnsId);

				this.domainDns = res;

            },

		}

	}

</script>