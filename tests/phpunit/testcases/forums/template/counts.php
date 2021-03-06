<?php

/**
 * Tests for the `bbp_*_forum_*_count()` template functions.
 *
 * @group forums
 * @group template
 * @group counts
 */
class BBP_Tests_Forums_Template_Counts extends BBP_UnitTestCase {

	/**
	 * @covers ::bbp_forum_subforum_count
	 * @covers ::bbp_get_forum_subforum_count
	 */
	public function test_bbp_get_forum_subforum_count() {
		$f1 = $this->factory->forum->create();
		$int_value = 3;
		$formatted_value = bbp_number_format( $int_value );

		$this->factory->forum->create_many( $int_value, array(
			'post_parent' => $f1,
		) );

		bbp_update_forum_subforum_count( $f1 );

		// Output.
		$count = bbp_get_forum_subforum_count( $f1, false );
		$this->expectOutputString( $formatted_value );
		bbp_forum_subforum_count( $f1 );

		// Formatted string.
		$count = bbp_get_forum_subforum_count( $f1, false );
		$this->assertSame( $formatted_value, $count );

		// Integer.
		$count = bbp_get_forum_subforum_count( $f1, true );
		$this->assertSame( $int_value, $count );

		// Direct query.
		$count = count( bbp_forum_query_subforum_ids( $f1 ) );
		$this->assertSame( $int_value, $count );
	}

	/**
	 * @covers ::bbp_forum_topic_count
	 * @covers ::bbp_get_forum_topic_count
	 */
	public function test_bbp_get_forum_topic_count() {
		$c = $this->factory->forum->create( array(
			'forum_meta' => array(
				'forum_type' => 'category',
			),
		) );

		$f = $this->factory->forum->create( array(
			'post_parent' => $c,
			'forum_meta' => array(
				'forum_id'   => $c,
			),
		) );

		$int_value = 3;
		$formatted_value = bbp_number_format( $int_value );

		$this->factory->topic->create_many( $int_value, array(
			'post_parent' => $f,
		) );

		bbp_update_forum_topic_count( $c );
		bbp_update_forum_topic_count( $f );

		// Forum output.
		$count = bbp_get_forum_topic_count( $f, true, false );
		$this->expectOutputString( $formatted_value );
		bbp_forum_topic_count( $f );

		// Forum formatted string.
		$count = bbp_get_forum_topic_count( $f, true, false );
		$this->assertSame( $formatted_value, $count );

		// Forum integer.
		$count = bbp_get_forum_topic_count( $f, true, true );
		$this->assertSame( $int_value, $count );

		// Category topic count.
		$count = bbp_get_forum_topic_count( $c, false, true );
		$this->assertSame( 0, $count );

		// Category total topic count.
		$count = bbp_get_forum_topic_count( $c, true, true );
		$this->assertSame( $int_value, $count );
	}

	/**
	 * @covers ::bbp_forum_reply_count
	 * @covers ::bbp_get_forum_reply_count
	 */
	public function test_bbp_get_forum_reply_count() {
		$c = $this->factory->forum->create( array(
			'forum_meta' => array(
				'forum_type' => 'category',
			),
		) );

		$f = $this->factory->forum->create( array(
			'post_parent' => $c,
			'forum_meta' => array(
				'forum_id'   => $c,
			),
		) );

		$t = $this->factory->topic->create( array(
			'post_parent' => $f,
		) );

		$int_value = 3;
		$formatted_value = bbp_number_format( $int_value );

		$this->factory->reply->create_many( $int_value, array(
			'post_parent' => $t,
		) );

		bbp_update_forum_reply_count( $c );
		bbp_update_forum_reply_count( $f );

		// Forum Output.
		$count = bbp_get_forum_reply_count( $f, true, false );
		$this->expectOutputString( $formatted_value );
		bbp_forum_reply_count( $f );

		// Forum formatted string.
		$count = bbp_get_forum_reply_count( $f, true, false );
		$this->assertSame( $formatted_value, $count );

		// Forum integer.
		$count = bbp_get_forum_reply_count( $f, true, true );
		$this->assertSame( $int_value, $count );

		// Category reply count.
		$count = bbp_get_forum_reply_count( $c, false, true );
		$this->assertSame( 0, $count );

		// Category total reply count.
		$count = bbp_get_forum_reply_count( $c, true, true );
		$this->assertSame( $int_value, $count );
	}

	/**
	 * @covers ::bbp_forum_post_count
	 * @covers ::bbp_get_forum_post_count
	 */
	public function test_bbp_get_forum_post_count() {
		$c = $this->factory->forum->create( array(
			'forum_meta' => array(
				'forum_type' => 'category',
			),
		) );

		$f = $this->factory->forum->create( array(
			'post_parent' => $c,
			'forum_meta' => array(
				'forum_id'   => $c,
			),
		) );

		$t = $this->factory->topic->create( array(
			'post_parent' => $f,
		) );

		$int_value = 3;

		// Topic + Replies.
		$result = 4;
		$formatted_result = bbp_number_format( $result );

		$this->factory->reply->create_many( $int_value, array(
			'post_parent' => $t,
		) );

		bbp_update_forum_topic_count( $c );
		bbp_update_forum_topic_count( $f );
		bbp_update_forum_reply_count( $c );
		bbp_update_forum_reply_count( $f );

		// Forum output.
		$count = bbp_get_forum_post_count( $f, true, false );
		$this->expectOutputString( $formatted_result );
		bbp_forum_post_count( $f );

		// Forum formatted string.
		$count = bbp_get_forum_post_count( $f, true, false );
		$this->assertSame( $formatted_result, $count );

		// Forum integer.
		$count = bbp_get_forum_post_count( $f, true, true );
		$this->assertSame( $result, $count );

		// Category post count.
		$count = bbp_get_forum_post_count( $c, false, true );
		$this->assertSame( 0, $count );

		// Category total post count.
		$count = bbp_get_forum_post_count( $c, true, true );
		$this->assertSame( $result, $count );
	}

	/**
	 * @covers ::bbp_forum_topic_count_hidden
	 * @covers ::bbp_get_forum_topic_count_hidden
	 */
	public function test_bbp_get_forum_topic_count_hidden() {
		$c = $this->factory->forum->create( array(
			'forum_meta' => array(
				'forum_type' => 'category',
			),
		) );

		$f = $this->factory->forum->create( array(
			'post_parent' => $c,
			'forum_meta' => array(
				'forum_id'   => $c,
			),
		) );

		$int_value = 3;
		$formatted_value = bbp_number_format( $int_value );

		$this->factory->topic->create_many( $int_value, array(
			'post_parent' => $f,
			'post_status' => bbp_get_spam_status_id(),
		) );

		bbp_update_forum_topic_count_hidden( $c );
		bbp_update_forum_topic_count_hidden( $f );

		// Forum output.
		$count = bbp_get_forum_topic_count_hidden( $f, false );
		$this->expectOutputString( $formatted_value );
		bbp_forum_topic_count_hidden( $f );

		// Forum formatted string.
		$count = bbp_get_forum_topic_count_hidden( $f, false );
		$this->assertSame( $formatted_value, $count );

		// Forum integer.
		$count = bbp_get_forum_topic_count_hidden( $f, true );
		$this->assertSame( $int_value, $count );

		// Category topic count hidden.
		$count = bbp_get_forum_topic_count_hidden( $c, true );
		$this->assertSame( 0, $count );

		// Category total topic count hidden.
		$count = bbp_get_forum_topic_count_hidden( $c, true );
		$this->assertSame( 0, $count );
	}
}
