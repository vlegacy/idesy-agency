<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package idesy
 */

get_header();
?>
<main class="main">
	<div class="works-page">
		<svg class="graphic-2" width="2154" height="1907" viewBox="0 0 2154 1907" fill="none"
			xmlns="http://www.w3.org/2000/svg">
			<g filter="url(#filter0_f_154_4)">
				<path class="path-graphic-2"
					d="M1014 1626.5C984.579 1462.66 1077 1177 1077 1177C1077 1177 285.744 872.828 257.5 825.5C229.256 778.172 1291.07 759.59 1291.07 759.59C1291.07 759.59 1296.1 140.218 1367.5 276.5C1575.31 673.124 1536.79 1264.63 1529.73 1352.27C1530.1 1351.98 1530.46 1351.7 1530.83 1351.41L1653.79 640.183L1897.91 1051.24C1897.91 1051.24 1724.26 1201.44 1530.83 1351.41L1528.78 1363.27C1528.78 1363.27 1529.15 1359.48 1529.73 1352.27C1294.35 1534.68 1030.13 1716.34 1014 1626.5Z"
					fill="url(#paint0_diamond_154_4)" fill-opacity="0.64" />
			</g>
			<defs>
				<filter id="filter0_f_154_4" x="0.94635" y="0.908508" width="2152.97" height="1905.3"
					filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
					<feFlood flood-opacity="0" result="BackgroundImageFix" />
					<feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
					<feGaussianBlur stdDeviation="128" result="effect1_foregroundBlur_154_4" />
				</filter>
				<radialGradient id="paint0_diamond_154_4" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
					gradientTransform="translate(1219.89 944.193) rotate(-120.706) scale(518.057 528.304)">
					<stop stop-color="#11376F" />
					<stop offset="1" stop-color="#116F64" />
				</radialGradient>
			</defs>
		</svg>
		<div class="container">
			<div aria-hidden="true" class="intro_title_line js-anim-intro-line" data-sroller-end="0"></div>
			<?php idesy_get_breadcrumbs(); ?>
			<div class="page-intro js-intro-w">
				<?php
				the_archive_title('<h1 class="h1 js-anim-intro-el">', '</h1>');
				?>
				<?php
				$latest_articles = new WP_Query(
					array(
						'post_type' => 'works',
						'posts_per_page' => 1,
						'orderby' => 'rand',
						'post_status' => 'publish',
					)
				);

				if ($latest_articles->have_posts()) :
					while ($latest_articles->have_posts()) :
						$latest_articles->the_post();
						get_template_part('template-parts/post_work_category');
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p>No articles found</p>';
				endif;
				?>
			</div>
			<div class="cat-filter" style="position: relative; z-index: 10;">
				<?php
				// Получаем объект текущего запроса (может быть категорией, тегом или чем-то ещё)
				$queried_object = get_queried_object();

				// 1. Получаем все записи (CPT 'works' или 'post')
				$args = array(
					'post_type'      => 'works',
					'posts_per_page' => -1,
				);

				$all_posts = get_posts($args);

				// Будем собирать все ID тегов в этом массиве
				$all_tag_ids = array();

				if (! empty($all_posts)) {
					// 2. Перебираем все посты
					foreach ($all_posts as $post) {
						$portfolio_tags_ids = get_post_meta($post->ID, 'works_post_tags', true);

						// Если в метаполе действительно массив ID
						if (! empty($portfolio_tags_ids) && is_array($portfolio_tags_ids)) {
							// Добавляем их в общий массив
							$all_tag_ids = array_merge($all_tag_ids, $portfolio_tags_ids);
						}
					}
				}

				// 3. Убираем дубли
				$all_tag_ids = array_unique($all_tag_ids);

				// 4. Если есть теги, выводим
				if (! empty($all_tag_ids)) {
					echo '<ul class="list-tags">';

					/**
					 * Проверяем, находимся ли мы сейчас именно на category/works/
					 * (т.е. на архиве рубрики со слагом works).
					 */
					$all_active_class = '';
					if (is_category('works')) {
						$all_active_class = 'active';
					}

					// Кнопка "All"
					echo '<li class="js-fade-el">';
					echo '<a href="' . esc_url('http://idesy/category/works/') . '" class="' . esc_attr($all_active_class) . '">';
					echo '<span>All</span></a></li>';

					// Теперь выводим остальные теги
					foreach ($all_tag_ids as $tag_id) {
						$term = get_term($tag_id);

						if ($term && ! is_wp_error($term)) {
							// Получаем ссылку на архив термина
							$term_link = get_term_link($term, $term->taxonomy);

							// По умолчанию класс пуст
							$term_active_class = '';

							// Если текущий архив (queried_object) — это именно этот термин, назначаем класс .active
							if (
								$queried_object instanceof WP_Term
								&& $queried_object->term_id === $term->term_id
							) {
								$term_active_class = 'active';
							}

							echo '<li class="js-fade-el">';
							echo '<a href="' . esc_url($term_link) . '" class="' . esc_attr($term_active_class) . '">';
							echo '<span>' . esc_html($term->name) . '</span></a>';
							echo '</li>';
						}
					}

					echo '</ul>';
				}
				?>

			</div>
			<div class="section_works">
				<div class="row">
					<div aria-hidden="true" class="work_title_line js-anim-line" data-sroller-end="1000"></div>
					<?php if (have_posts()) : ?>
					<?php
						/* Start the Loop */
						while (have_posts()) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */

							get_template_part('template-parts/content', get_post_type());


						endwhile;

						// Pagination using paginate_links()
						the_posts_pagination(
							array(
								'prev_text' => __('Previous', 'textdomain'),
								'next_text' => __('Next', 'textdomain'),
							)
						);

					else :

						get_template_part('template-parts/content-works', 'none');

					endif;
					?>
				</div>
			</div>
			<div class="block-start">
				<div aria-hidden="true" class="work_start_line js-anim-line" data-sroller-end="1000"></div>
				<div class="container">
					<span class="js-fade-el">
						<?php
						$random_case_title = get_field('section_ready-to-start-title', 'option');
						if ($random_case_title) {
							echo $random_case_title;
						}
						?>
					</span>
				</div>
			</div>
		</div>
		<svg class="bg-gradient" width="1920" height="1704" viewBox="0 0 1920 1704" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g filter="url(#filter0_f_1086_525)">
				<path d="M871.5 713L969.5 144L1181 727.5L1843.5 951.5L1047.5 1052L964.5 1448L878.5 1033.5L-97 802.5L871.5 713Z" fill="url(#paint0_diamond_1086_525)" fill-opacity="0.48" />
			</g>
			<defs>
				<filter id="filter0_f_1086_525" x="-353" y="-112" width="2452.5" height="1816" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
					<feFlood flood-opacity="0" result="BackgroundImageFix" />
					<feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
					<feGaussianBlur stdDeviation="128" result="effect1_foregroundBlur_1086_525" />
				</filter>
				<radialGradient id="paint0_diamond_1086_525" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(847.038 809.332) rotate(90) scale(636.965 636.538)">
					<stop stop-color="#11376F" />
					<stop offset="1" stop-color="#116F64" />
				</radialGradient>
			</defs>
		</svg>
	</div>
</main><!-- #main -->
<?php

get_footer();
