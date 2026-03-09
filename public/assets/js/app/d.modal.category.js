/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

/* Prevent errors, If these variables are missing. */
if (typeof categoryWasSelected === 'undefined') {
	var categoryWasSelected = false;
}
if (typeof packageIsEnabled === 'undefined') {
	var packageIsEnabled = false;
}
if (typeof editLabel === 'undefined') {
	var editLabel = 'Edit';
}

onDocumentReady((event) => {
	
	/* Select a category */
	getCategories(siteUrl, languageCode);
	$(document).on('click', '.modal-cat-link, #selectCats .page-link', function (e) {
		e.preventDefault(); /* Prevents submission or reloading */
		getCategories(siteUrl, languageCode, this);
	});
	
});

/**
 * Get subcategories buffer and/or Append selected category
 *
 * @param siteUrl
 * @param languageCode
 * @param jsThis
 * @returns {boolean}
 */
function getCategories(siteUrl, languageCode, jsThis = null) {
	let csrfToken = $('input[name=_token]').val();
	
	/* Get Request URL */
	let url;
	
	let selectedId = $('#categoryId').val();
	let beingSelectedId;
	let selectedManually = false;
	
	if (!isDefined(jsThis) || jsThis === null) {
		/* On page load, without click on the modal link */
		// ---
		beingSelectedId = !isEmpty(selectedId) ? selectedId : 0;
		
		/* Set the global selection URL */
		url = `${siteUrl}/browsing/categories/select`;
		
		if (!categoryWasSelected) {
			return false;
		}
		
	} else {
		/* Click on the modal link */
		// ---
		const thisEl = $(jsThis);
		selectedManually = true;
		
		/* Get the category selection URL */
		url = thisEl.attr('href');
		
		if (thisEl.hasClass('page-link')) {
			/* Get URL from pagination link */
			// ---
			
			/* Extract the category ID */
			beingSelectedId = 0;
			if (!isEmpty(url)) {
				beingSelectedId = urlBuilder(url).getParameter('parentId') ?? 0;
			}
			
		} else {
			/* Get URL from data-selection-url */
			// ---
			
			if (thisEl.hasClass('open-selection-url')) {
				url = thisEl.data('selection-url');
			} else {
				/* Get the category ID */
				beingSelectedId = thisEl.data('id');
				beingSelectedId = !isEmpty(beingSelectedId) ? beingSelectedId : 0;
			}
			
		}
		
		/*
		 * Optimize the category selection
		 * by preventing AJAX request to append the selection
		 */
		let hasChildren = thisEl.data('has-children');
		if (isDefined(hasChildren) && (hasChildren === 0 || hasChildren === '0')) {
			let catName = thisEl.text();
			let catParentId = thisEl.data('parent-id');
			let catParentUrl = urlBuilder(url).setParameters({parentId: catParentId}).toString();
			
			let linkText = `<i class="fa-regular fa-pen-to-square"></i> ${editLabel}`;
			let outputHtml = catName
				+ `[ <a href="#browseCategories"
						data-bs-toggle="modal"
						class="modal-cat-link open-selection-url link-primary text-decoration-none"
						data-selection-url="${catParentUrl}"
					>${linkText}</a> ]`;
			
			return appendSelectedCategory(beingSelectedId, outputHtml, selectedManually);
		}
	}
	
	const payload = {
		'parentId': beingSelectedId
	};
	if (!isEmpty(selectedId)) {
		payload['selectedId'] = selectedId;
	}
	
	/* AJAX Call */
	const ajax = $.ajax({
		method: 'GET',
		url: url,
		data: payload,
		beforeSend: function() {
			/*
			let spinner = '<i class="spinner-border"></i>';
			$('#selectCats').addClass('text-center').html(spinner);
			*/
			
			const selectCatsEl = $('#selectCats');
			selectCatsEl.empty().addClass('py-4').busyLoad('hide');
			selectCatsEl.busyLoad('show', {
				text: langLayout.loading,
				custom: createCustomSpinnerEl(),
				containerItemClass: 'm-5',
			});
		}
	});
	ajax.done(function (xhr) {
		const selectCatsEl = $('#selectCats');
		selectCatsEl.removeClass('py-4').busyLoad('hide');
		
		if (!isDefined(xhr.html) || !isDefined(xhr.hasChildren)) {
			return false;
		}
		
		/* Get & append the category's children */
		if (xhr.hasChildren) {
			selectCatsEl.removeClass('text-center');
			selectCatsEl.html(xhr.html);
		} else {
			/*
			 * Section to append default category field info
			 * or to append selected category during form loading.
			 * Not intervene when the onclick event is fired.
			 */
			if (!isDefined(xhr.category) || !isDefined(xhr.category.id) || !isDefined(xhr.html)) {
				return false;
			}
			
			return appendSelectedCategory(xhr.category.id, xhr.html, selectedManually);
		}
	});
	ajax.fail(function(xhr) {
		let message = getErrorMessageFromXhr(xhr);
		if (message !== null) {
			jsAlert(message, 'error', false, true);
			
			/* Close the Modal */
			const modalEl = document.querySelector('#browseCategories');
			if (typeof modalEl !== 'undefined' && modalEl !== null) {
				const modalObj = bootstrap.Modal.getInstance(modalEl);
				if (modalObj !== null) {
					modalObj.hide();
				}
			}
		}
	});
}

/**
 * Append the selected category to its field in the form
 *
 * @param catId
 * @param outputHtml
 * @param selectedManually
 * @returns {boolean}
 */
function appendSelectedCategory(catId, outputHtml, selectedManually) {
	if (!isDefined(catId) || !isDefined(outputHtml)) {
		return false;
	}
	
	try {
		/* Select the category & append it */
		$('#catsContainer').html(outputHtml);
		
		/* Save data in hidden field */
		const categoryIdEl = document.getElementById('categoryId');
		if (categoryIdEl) {
			categoryIdEl.value = catId;
			if (selectedManually) {
				categoryIdEl.dispatchEvent(new Event('input', {bubbles: true}));
			}
		}
		
		/* Close the Modal */
		const modalEl = document.querySelector('#browseCategories');
		if (isDefined(modalEl) && modalEl !== null) {
			const modalObj = bootstrap.Modal.getInstance(modalEl);
			if (modalObj !== null) {
				modalObj.hide();
			}
		}
	} catch (e) {
		console.log(e);
	}
	
	return false;
}
