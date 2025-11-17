<?php
/**
 * BLACKLIST TESTING SCRIPT
 * Test blacklist functionality without browser
 * 
 * Usage: php TEST_BLACKLIST.php
 */

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/helpers.php';

use App\Models\WordBlacklist;

echo "========================================\n";
echo "🧪 BLACKLIST TESTING SCRIPT\n";
echo "========================================\n\n";

// Initialize blacklist model
$blacklist = new WordBlacklist();

echo "1. Testing Model Connection...\n";
try {
    $words = $blacklist->getActiveWords();
    echo "✅ SUCCESS: Found " . count($words) . " active blacklisted words\n\n";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Test Cases
$testCases = [
    // Should be REJECTED
    [
        'content' => 'Mari bermain slot online di situs kami',
        'expected' => 'auto_reject',
        'description' => 'Critical spam (slot)'
    ],
    [
        'content' => 'Daftar sekarang di situs judi terpercaya',
        'expected' => 'auto_reject',
        'description' => 'Critical spam (judi)'
    ],
    [
        'content' => 'Togel online maxwin gacor hari ini',
        'expected' => 'auto_reject',
        'description' => 'Multiple critical words'
    ],
    [
        'content' => 'Bonus new member 100% untuk slot gacor',
        'expected' => 'auto_reject',
        'description' => 'Promo judi'
    ],
    
    // Should be BLOCKED
    [
        'content' => 'Saya menang jackpot besar sekali',
        'expected' => 'block',
        'description' => 'Suspicious word (jackpot)'
    ],
    [
        'content' => 'Link alternatif untuk akses situs',
        'expected' => 'block',
        'description' => 'Suspicious phrase'
    ],
    
    // Should be FLAGGED
    [
        'content' => 'Bagaimana cara deposit ke rekening?',
        'expected' => 'flag',
        'description' => 'Low risk word (deposit)'
    ],
    [
        'content' => 'Ada bonus untuk member baru?',
        'expected' => 'flag',
        'description' => 'Low risk word (bonus)'
    ],
    
    // Should PASS
    [
        'content' => 'Bagaimana cara mendapatkan sertifikat halal?',
        'expected' => 'pass',
        'description' => 'Clean content'
    ],
    [
        'content' => 'Terima kasih atas informasinya yang bermanfaat',
        'expected' => 'pass',
        'description' => 'Normal comment'
    ]
];

echo "2. Running Test Cases...\n";
echo "========================================\n\n";

$passed = 0;
$failed = 0;

foreach ($testCases as $i => $test) {
    $num = $i + 1;
    echo "Test #{$num}: {$test['description']}\n";
    echo "Content: \"{$test['content']}\"\n";
    
    $result = $blacklist->checkContent($test['content']);
    
    $action = $result['has_blacklist'] ? $result['action'] : 'pass';
    
    echo "Expected: {$test['expected']}\n";
    echo "Got: {$action}\n";
    
    if ($action === $test['expected']) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        echo "❌ FAIL\n";
        $failed++;
        
        if ($result['has_blacklist']) {
            echo "Detected words: ";
            foreach ($result['detected_words'] as $word) {
                echo "[{$word['word']}({$word['severity']})] ";
            }
            echo "\n";
        }
    }
    
    echo "\n";
}

echo "========================================\n";
echo "📊 TEST RESULTS:\n";
echo "========================================\n";
echo "Total Tests: " . count($testCases) . "\n";
echo "✅ Passed: {$passed}\n";
echo "❌ Failed: {$failed}\n";
echo "Success Rate: " . round(($passed / count($testCases)) * 100, 2) . "%\n\n";

if ($failed === 0) {
    echo "🎉 ALL TESTS PASSED!\n";
    echo "Blacklist system is working correctly.\n";
} else {
    echo "⚠️ SOME TESTS FAILED!\n";
    echo "Please check the blacklist configuration.\n";
}

echo "\n3. Testing Helper Functions...\n";
echo "========================================\n";

// Test helper functions
$testContent = "Mari bermain slot online gacor";
echo "Testing: check_blacklist()\n";
$helperResult = check_blacklist($testContent);
echo "Result: " . ($helperResult['has_blacklist'] ? 'DETECTED' : 'CLEAN') . "\n";
echo $helperResult['has_blacklist'] ? "✅ PASS\n" : "❌ FAIL\n";
echo "\n";

echo "Testing: is_content_safe()\n";
$isSafe = is_content_safe($testContent);
echo "Result: " . ($isSafe ? 'SAFE' : 'BLOCKED') . "\n";
echo !$isSafe ? "✅ PASS\n" : "❌ FAIL\n";
echo "\n";

echo "Testing: get_blacklist_error()\n";
$error = get_blacklist_error($helperResult);
echo "Error: {$error}\n";
echo !empty($error) ? "✅ PASS\n" : "❌ FAIL\n";
echo "\n";

echo "========================================\n";
echo "✨ TESTING COMPLETE!\n";
echo "========================================\n";

// Show some blacklisted words
echo "\n4. Sample Blacklisted Words:\n";
echo "========================================\n";
$critical = $blacklist->getWordsBySeverity('critical');
$high = $blacklist->getWordsBySeverity('high');

echo "Critical ({count($critical)} words): ";
$criticalWords = array_slice(array_column($critical, 'word'), 0, 5);
echo implode(', ', $criticalWords) . ", ...\n";

echo "High ({count($high)} words): ";
$highWords = array_slice(array_column($high, 'word'), 0, 5);
echo implode(', ', $highWords) . ", ...\n";

echo "\nFor complete list, login to /admin/blacklist\n";
echo "========================================\n";
