<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_greetings;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/greetings/lib.php');
/**
 * Tests for Greetings
 *
 * @package    local_greetings
 * @category   test
 * @copyright  2026 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class lib_test extends \advanced_testcase
{
    /**
     * Example of a unittest
     *
     * TODO change the 'covers' tag to the class or function in the plugin.
     * @covers ::get_config
     */
    public function test_local_greetings_null_user(): void
    {
        $this->resetAfterTest();

        // Test null user case.
        $result = local_greetings_get_greeting_msg(null);
        $expected = get_string('greetingloggedinuser', 'local_greetings');
        $this->assertEquals($expected, $result);
    }

    public function test_local_greetings_au_user(): void
    {
        $this->resetAfterTest();

        // Test user with country='AU'.
        $user = $this->getDataGenerator()->create_user(); // Create a new user.
        $user->country = 'AU';

        $result = local_greetings_get_greeting_msg($user);
        $expected = get_string('greetinguserau', 'local_greetings', fullname($user));
        $this->assertEquals($expected, $result);
    }

    /**
     * Testing the translation of greeting messages.
     *
     * @covers ::local_greetings_get_greeting
     *
     * @dataProvider local_greetings_get_greeting_provider
     * @param string|null $country User country
     * @param string $langstring Greetings message language string
     */
    public function test_local_greetings_get_greeting(?string $country, string $langstring): void
    {
        $user = null;
        if (!empty($country)) {
            $this->resetAfterTest(true);
            $user = $this->getDataGenerator()->create_user();
            $user->country = $country;
        }
        $name = fullname($user);
        $expected = get_string($langstring, 'local_greetings', $name);
        $actual = local_greetings_get_greeting_msg($user);
        $this->assertSame($expected, $actual);
    }

    /**
     * Data provider for {@see test_local_greetings_get_greeting()}.
     *
     * @return array List of data sets - (string) data set name => (array) data
     */
    public static function local_greetings_get_greeting_provider(): array
    {
        return [
            'No user' => [ //  logged in.
                'country' => null,
                'langstring' => 'greetingloggedinuser',
            ],
            'AU user' => [
                'country' => 'AU',
                'langstring' => 'greetinguserau',
            ],
            'ES user' => [
                'country' => 'ES',
                'langstring' => 'greetinguseres',
            ],
            'VU user' => [ // Logged in user, but no local greeting.
                'country' => 'VU',
                'langstring' => 'greetingloggedinuser',
            ],
            'UK user' => [ // UNDEFINED CONTERY, SHOULD DEFFALUT TO THE DEFAULT IN THE SWITCH CASE.
                'country' => 'UK',
                'langstring' => 'greetingloggedinuser'
            ]
        ];
    }
}
