import { createElement } from '@wordpress/element'
import cls from 'classnames'

import { __ } from 'ct-i18n'
import { Select, EntityIdPicker } from 'blocksy-options'
import useConditionsData from './useConditionsData'

import ExpireCondition from './ExpireCondition'
import ScheduleDate from './ScheduleDate'

const SingleCondition = ({ className = '', condition, onRemove, onChange }) => {
	condition = {
		...condition,
		payload: {
			...condition.payload,
		},
	}

	const { allRules, allLanguages } = useConditionsData(condition)

	const ruleDescriptor = allRules.find(({ key }) => key === condition.rule)

	const hasAdditions =
		condition.rule === 'post_ids' ||
		condition.rule === 'page_ids' ||
		condition.rule === 'product_ids' ||
		condition.rule === 'custom_post_type_ids' ||
		condition.rule === 'taxonomy_ids' ||
		condition.rule === 'post_with_taxonomy_ids' ||
		condition.rule === 'card_product_with_taxonomy_ids' ||
		condition.rule === 'card_post_with_taxonomy_ids' ||
		condition.rule === 'product_with_taxonomy_ids' ||
		condition.rule === 'product_taxonomy_ids' ||
		condition.rule === 'current_language' ||
		condition.rule === 'user_post_author_id' ||
		condition.rule === 'author' ||
		condition.rule === 'start_end_date' ||
		condition.rule === 'schedule_date' ||
		condition.rule === 'request_referer' ||
		condition.rule === 'request_cookie' ||
		condition.rule === 'request_url' ||
		(ruleDescriptor &&
			ruleDescriptor.sub_ids &&
			ruleDescriptor.sub_ids.length > 0) ||
		(ruleDescriptor &&
			ruleDescriptor.is_custom_rule &&
			ruleDescriptor.has_text_field) ||
		(ruleDescriptor &&
			ruleDescriptor.is_custom_rule &&
			ruleDescriptor.choices)

	return (
		<div
			className={cls('ct-condition-group', className, {
				'ct-cols-3': hasAdditions,
				'ct-cols-2': !hasAdditions,
			})}>
			<Select
				key="first"
				option={{
					inputClassName: 'ct-condition-type',
					selectInputStart: () => {
						if (condition.type === 'include') {
							return (
								<span className={`ct-include`}>
									<svg
										width="8"
										height="8"
										fill="currentColor"
										viewBox="0 0 20 20">
										<path d="M20,11h-9v9H9v-9H0V9h9V0h2v9h9V11z" />
									</svg>
								</span>
							)
						}

						if (condition.type === 'exclude') {
							return (
								<span className={`ct-exclude`}>
									<svg
										width="8"
										height="8"
										fill="currentColor"
										viewBox="0 0 20 20">
										<path d="M20,9v2H0V9H20z" />
									</svg>
								</span>
							)
						}

						return null
					},
					placeholder: __('Select variation', 'blocksy-companion'),
					choices: {
						include: __('Include', 'blocksy-companion'),
						exclude: __('Exclude', 'blocksy-companion'),
					},
				}}
				value={condition.type}
				onChange={(type) => {
					onChange({
						...condition,
						type,
					})
				}}
			/>

			<Select
				key="second"
				option={{
					appendToBody: true,
					placeholder: __('Select rule', 'blocksy-companion'),
					choices: allRules,
					search: true,
				}}
				value={condition.rule}
				onChange={(rule) => {
					const ruleDescriptor = allRules.find(
						({ key }) => key === rule
					)

					if (
						ruleDescriptor.sub_ids &&
						ruleDescriptor.sub_ids.length > 0
					) {
						onChange({
							...condition,
							rule: ruleDescriptor.sub_ids[0].id,
						})
						return
					}

					onChange({
						...condition,
						rule,
					})
				}}
			/>

			{(condition.rule === 'post_ids' ||
				condition.rule === 'custom_post_type_ids' ||
				condition.rule === 'product_ids' ||
				condition.rule === 'page_ids') && (
				<EntityIdPicker
					option={{
						placeholder:
							condition.rule === 'post_ids'
								? __('Select post', 'blocksy-companion')
								: condition.rule === 'page_ids'
								? __('Select page', 'blocksy-companion')
								: condition.rule === 'product_ids'
								? __('Select product', 'blocksy-companion')
								: __(
										'Custom Post Type ID',
										'blocksy-companion'
								  ),
						entity: 'posts',
						post_type: {
							post_ids: 'post',
							page_ids: 'page',
							product_ids: 'product',
							custom_post_type_ids: 'ct_cpt',
						}[condition.rule],
					}}
					value={(condition.payload || {}).post_id || ''}
					onChange={(id) => {
						onChange({
							...condition,
							payload: {
								...condition.payload,
								post_id: id,
							},
						})
					}}
				/>
			)}

			{(condition.rule === 'taxonomy_ids' ||
				condition.rule === 'post_with_taxonomy_ids' ||
				condition.rule === 'card_post_with_taxonomy_ids') && (
				<EntityIdPicker
					option={{
						placeholder: __('Select taxonomy', 'blocksy-companion'),
						entity: 'taxonomies',
					}}
					value={(condition.payload || {}).taxonomy_id || ''}
					onChange={(id) => {
						onChange({
							...condition,
							payload: {
								...condition.payload,
								taxonomy_id: id,
							},
						})
					}}
				/>
			)}

			{(condition.rule === 'product_with_taxonomy_ids' ||
				condition.rule === 'product_taxonomy_ids' ||
				condition.rule === 'card_product_with_taxonomy_ids') && (
				<EntityIdPicker
					option={{
						placeholder: __('Select taxonomy', 'blocksy-companion'),
						entity: 'taxonomies',
						post_type: 'product',
					}}
					value={(condition.payload || {}).taxonomy_id || ''}
					onChange={(id) => {
						onChange({
							...condition,
							payload: {
								...condition.payload,
								taxonomy_id: id,
							},
						})
					}}
				/>
			)}

			{condition.rule === 'current_language' && (
				<Select
					option={{
						appendToBody: true,
						defaultToFirstItem: false,
						placeholder: __('Select language', 'blocksy-companion'),
						choices: allLanguages.map((language) => ({
							key: language.id,
							value: language.name,
						})),
						search: true,
					}}
					value={(condition.payload || {}).language || ''}
					onChange={(language) => {
						onChange({
							...condition,
							payload: {
								...condition.payload,
								language,
							},
						})
					}}
				/>
			)}

			{condition.rule === 'user_post_author_id' && (
				<EntityIdPicker
					option={{
						placeholder: __('Select user', 'blocksy-companion'),
						entity: 'users',
						additionOptions: [
							{
								key: 'current_user',
								value: __('Current user', 'blocksy-companion'),
							},
						],
					}}
					value={(condition.payload || {}).user_id || ''}
					onChange={(id) => {
						onChange({
							...condition,
							payload: {
								...condition.payload,
								user_id: id,
							},
						})
					}}
				/>
			)}

			{condition.rule === 'author' && (
				<EntityIdPicker
					option={{
						placeholder: __('Select user', 'blocksy-companion'),
						entity: 'users',
						additionOptions: [
							{
								key: 'all_users',
								value: __('All authors', 'blocksy-companion'),
							},
						],
					}}
					value={(condition.payload || {}).user_id || 'all_users'}
					onChange={(id) => {
						onChange({
							...condition,
							payload: {
								...condition.payload,
								user_id: id,
							},
						})
					}}
				/>
			)}

			{ruleDescriptor &&
				ruleDescriptor.sub_ids &&
				ruleDescriptor.sub_ids.length > 0 && (
					<Select
						option={{
							appendToBody: true,
							placeholder: __(
								'Select sub field',
								'blocksy-companion'
							),
							choices: ruleDescriptor.sub_ids.map((subId) => ({
								key: subId.id,
								value: subId.title,
							})),
							search: true,
							inputClassName: 'ct-dropdown-normal-width',
						}}
						value={condition.rule}
						onChange={(sub_id) => {
							onChange({
								...condition,
								rule: sub_id,
							})
						}}
					/>
				)}

			{condition.rule === 'start_end_date' && (
				<ExpireCondition condition={condition} onChange={onChange} />
			)}
			{condition.rule === 'schedule_date' && (
				<ScheduleDate condition={condition} onChange={onChange} />
			)}

			{condition.rule === 'request_referer' && (
				<div className="ct-option-input">
					<input
						type="text"
						placeholder="website.com"
						value={condition.payload.referer}
						onChange={(e) => {
							onChange({
								...condition,
								payload: {
									...condition.payload,
									referer: e.target.value,
								},
							})
						}}
					/>

					<span
						className="ct-condition-info"
						data-tooltip-reveal="top">
						<svg width="16" height="16" viewBox="0 0 24 24">
							<path d="M12,2C6.477,2,2,6.477,2,12s4.477,10,10,10s10-4.477,10-10S17.523,2,12,2z M12,17L12,17c-0.552,0-1-0.448-1-1v-4 c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v4C13,16.552,12.552,17,12,17z M12.5,9h-1C11.224,9,11,8.776,11,8.5v-1 C11,7.224,11.224,7,11.5,7h1C12.776,7,13,7.224,13,7.5v1C13,8.776,12.776,9,12.5,9z"></path>
						</svg>

						<i className="ct-tooltip">
							{__(
								'Display based on referer domain',
								'blocksy-companion'
							)}
						</i>
					</span>
				</div>
			)}

			{condition.rule === 'request_cookie' && (
				<div className="ct-option-input">
					<input
						type="text"
						placeholder="cookie_name"
						value={(condition.payload || {}).cookie || ''}
						onChange={(e) => {
							onChange({
								...condition,
								payload: {
									...condition.payload,
									cookie: e.target.value,
								},
							})
						}}
					/>

					<span
						className="ct-condition-info"
						data-tooltip-reveal="top">
						<svg width="16" height="16" viewBox="0 0 24 24">
							<path d="M12,2C6.477,2,2,6.477,2,12s4.477,10,10,10s10-4.477,10-10S17.523,2,12,2z M12,17L12,17c-0.552,0-1-0.448-1-1v-4 c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v4C13,16.552,12.552,17,12,17z M12.5,9h-1C11.224,9,11,8.776,11,8.5v-1 C11,7.224,11.224,7,11.5,7h1C12.776,7,13,7.224,13,7.5v1C13,8.776,12.776,9,12.5,9z"></path>
						</svg>

						<i className="ct-tooltip">
							{__(
								'Display if cookie is present',
								'blocksy-companion'
							)}
						</i>
					</span>
				</div>
			)}

			{condition.rule === 'request_url' && (
				<div className="ct-option-input">
					<input
						type="text"
						placeholder="example=campaignID"
						value={condition.payload.url || ''}
						onChange={(e) => {
							onChange({
								...condition,
								payload: {
									...condition.payload,
									url: e.target.value,
								},
							})
						}}
					/>

					<span
						className="ct-condition-info"
						data-tooltip-reveal="top">
						<svg width="16" height="16" viewBox="0 0 24 24">
							<path d="M12,2C6.477,2,2,6.477,2,12s4.477,10,10,10s10-4.477,10-10S17.523,2,12,2z M12,17L12,17c-0.552,0-1-0.448-1-1v-4 c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v4C13,16.552,12.552,17,12,17z M12.5,9h-1C11.224,9,11,8.776,11,8.5v-1 C11,7.224,11.224,7,11.5,7h1C12.776,7,13,7.224,13,7.5v1C13,8.776,12.776,9,12.5,9z"></path>
						</svg>

						<i className="ct-tooltip">
							{__(
								'Display if query string is present in URL',
								'blocksy-companion'
							)}
						</i>
					</span>
				</div>
			)}

			{ruleDescriptor &&
				ruleDescriptor.is_custom_rule &&
				ruleDescriptor.has_text_field && (
					<div className="ct-option-input">
						<input
							type="text"
							placeholder=""
							value={condition.payload.value || ''}
							onChange={(e) => {
								onChange({
									...condition,
									payload: {
										...condition.payload,
										value: e.target.value,
									},
								})
							}}
						/>
					</div>
				)}

			{ruleDescriptor &&
				ruleDescriptor.is_custom_rule &&
				ruleDescriptor.choices && (
					<Select
						option={{
							appendToBody: true,
							placeholder: __(
								'Select value',
								'blocksy-companion'
							),
							choices: ruleDescriptor.choices,

							search: true,
							inputClassName: 'ct-dropdown-normal-width',
						}}
						value={
							condition.payload.value ||
							ruleDescriptor.choices[0].key
						}
						onChange={(value) => {
							onChange({
								...condition,
								payload: {
									...condition.payload,
									value,
								},
							})
						}}
					/>
				)}

			<button
				type="button"
				className="ct-remove-condition-group"
				onClick={(e) => {
					e.preventDefault()
					onRemove()
				}}>
				<svg
					width="7px"
					height="7px"
					fill="currentColor"
					viewBox="0 0 24 24">
					<path d="m12 14.7 9.3 9.3 2.7-2.7-9.3-9.3L24 2.7 21.3 0 12 9.3 2.7 0 0 2.7 9.3 12 0 21.3 2.7 24l9.3-9.3z"></path>
				</svg>
			</button>
		</div>
	)
}

export default SingleCondition
