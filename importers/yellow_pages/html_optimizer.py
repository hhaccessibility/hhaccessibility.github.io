import lxml.html as html


def optimize_html(content):
	"""
	Removes some content that isn't needed.
	This is to save space on disk and make it quicker to process later.
	"""
	print('optimize_html called')
	document = html.fromstring(content)
	css_selectors = ['title', 'style', 'form', '.jsAlphaContainer', 'script[async]', 'script[src]',
		'.jsMessageMerchantConfig', '.fineprint', '.ypgFooterLinks', '#auth-resource', '#autocompleteTemplate',
		'#feature-where-smart-autocomplete', '#serpDeals-resource', '#side-menu--left',
		'#whereFieldTemplate', '#previousLocation', '[type="text/x-handlebars-template"]',
		'#page-configuration-resource', '.jsViewMoreMobileConfig', '#ypSensitiveHeading',
		'#rateResultsAnalytics', '#ypg_mediative_bigbox', '.jsGenericRedirectConfig',
		'.jsSuggestedLocationsConfig', '.contentControls.listing-summary', '.page__header.jsHeader'
		'link[rel="dns-prefetch"]', 'link[rel="stylesheet"]', '.filters__footer__buttons',
		'#jsGenConfig', '.jsIsSafariOrIE', '.jsNewsletterModalExitConfig', '#proxy-yid-config',
		'#jsCommonTranslations', '.modal-dialog.modal-md', '.jsYPTopSites',
		'[http-equiv]', 'meta[property]', '#autocomplete-analytics-resource']
	for css_selector in css_selectors:
		for bad in document.cssselect(css_selector):
			bad.getparent().remove(bad)

	# Remove any script elements that contain any of the list of substrings.
	# These script elements don't help us extract useful information from the downloaded data.
	useless_script_element_tokens = ['window.NREUM||', 'aJsFiles.length',
		'YP.smartScroll', '$YPCA.getDefer', 'c.className.replace(/no-js/, \'js\')',
		'function detectMobile()', 'jQuery(document).ready(function ($)', 'function logRequest()',
		'var aJsFiles']
	for script_element in document.cssselect('script'):
		content = script_element.text_content()
		found = False
		for token in useless_script_element_tokens:
			if token in content:
				found = True
				break
		if found:
			script_element.getparent().remove(script_element)

	print('optimize_html called and complete')
	return html.tostring(document, pretty_print=True)
