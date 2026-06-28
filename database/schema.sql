-- =====================================================================
--  Mipo Dadang Leadership Foundation (MDLF)
--  Database schema + seed data
--  Import this file in cPanel > phpMyAdmin (select your database first),
--  OR run /install.php once after uploading.
--
--  Default accounts (CHANGE THE PASSWORDS AFTER FIRST LOGIN):
--    Admin   ->  admin@mdlf.org      / admin1234
--    Member  ->  disciple@mdlf.org   / disciple1234
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------- Users (admins + portal members) --------------------------
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(120) NOT NULL,
  email         VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('admin','member') NOT NULL DEFAULT 'member',
  phone         VARCHAR(40)  DEFAULT NULL,
  location      VARCHAR(120) DEFAULT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Site settings (editable in admin) ------------------------
CREATE TABLE IF NOT EXISTS settings (
  name  VARCHAR(80) NOT NULL,
  value TEXT,
  PRIMARY KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Impact statistics (home page counters) -------------------
CREATE TABLE IF NOT EXISTS impact_stats (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  label      VARCHAR(160) NOT NULL,
  value      VARCHAR(40)  NOT NULL,
  suffix     VARCHAR(20)  DEFAULT '',
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Editable pages --------------------------------------------
CREATE TABLE IF NOT EXISTS pages (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title       VARCHAR(200) NOT NULL,
  slug        VARCHAR(220) NOT NULL,
  content     MEDIUMTEXT,
  status      ENUM('draft','published') NOT NULL DEFAULT 'published',
  sort_order  INT NOT NULL DEFAULT 0,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Foundation objectives (home page "commitments") -----------
CREATE TABLE IF NOT EXISTS objectives (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title       VARCHAR(200) NOT NULL,
  description TEXT,
  sort_order  INT NOT NULL DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Page blocks (WordPress-style page builder) ----------------
CREATE TABLE IF NOT EXISTS blocks (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  page_id    INT UNSIGNED NOT NULL,
  type       VARCHAR(60) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  data       MEDIUMTEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_blocks_page (page_id),
  CONSTRAINT fk_blocks_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Media library (uploaded images / audio / video) -----------
CREATE TABLE IF NOT EXISTS media (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  filename      VARCHAR(255) NOT NULL,
  type          VARCHAR(20)  NOT NULL DEFAULT 'image',
  mime          VARCHAR(120) DEFAULT NULL,
  original_name VARCHAR(255) DEFAULT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- News / blog posts ----------------------------------------
CREATE TABLE IF NOT EXISTS posts (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title        VARCHAR(200) NOT NULL,
  slug         VARCHAR(220) NOT NULL,
  excerpt      VARCHAR(400) DEFAULT NULL,
  body         MEDIUMTEXT,
  cover_image  VARCHAR(255) DEFAULT NULL,
  status       ENUM('draft','published') NOT NULL DEFAULT 'draft',
  published_at DATETIME DEFAULT NULL,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_posts_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Discipleship modules -------------------------------------
CREATE TABLE IF NOT EXISTS modules (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title       VARCHAR(200) NOT NULL,
  slug        VARCHAR(220) NOT NULL,
  summary     VARCHAR(400) DEFAULT NULL,
  description MEDIUMTEXT,
  cover_image VARCHAR(255) DEFAULT NULL,
  scripture   VARCHAR(160) DEFAULT NULL,
  sort_order  INT NOT NULL DEFAULT 0,
  status      ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_modules_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Lessons (belong to a module) -----------------------------
CREATE TABLE IF NOT EXISTS lessons (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  module_id    INT UNSIGNED NOT NULL,
  title        VARCHAR(200) NOT NULL,
  slug         VARCHAR(220) NOT NULL,
  summary      VARCHAR(400) DEFAULT NULL,
  content      MEDIUMTEXT,
  duration_min INT NOT NULL DEFAULT 10,
  sort_order   INT NOT NULL DEFAULT 0,
  status       ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_lessons_slug (slug),
  KEY idx_lessons_module (module_id),
  CONSTRAINT fk_lessons_module FOREIGN KEY (module_id)
    REFERENCES modules (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Lesson resources (downloads / links / videos) ------------
CREATE TABLE IF NOT EXISTS resources (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  lesson_id  INT UNSIGNED NOT NULL,
  label      VARCHAR(200) NOT NULL,
  type       ENUM('video','pdf','link','scripture','audio') NOT NULL DEFAULT 'link',
  url        VARCHAR(500) DEFAULT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_resources_lesson (lesson_id),
  CONSTRAINT fk_resources_lesson FOREIGN KEY (lesson_id)
    REFERENCES lessons (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Per-member lesson progress -------------------------------
CREATE TABLE IF NOT EXISTS lesson_progress (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id      INT UNSIGNED NOT NULL,
  lesson_id    INT UNSIGNED NOT NULL,
  completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_progress (user_id, lesson_id),
  KEY idx_progress_user (user_id),
  CONSTRAINT fk_progress_user   FOREIGN KEY (user_id)   REFERENCES users (id)   ON DELETE CASCADE,
  CONSTRAINT fk_progress_lesson FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Contact / partner messages -------------------------------
CREATE TABLE IF NOT EXISTS messages (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(190) NOT NULL,
  subject    VARCHAR(200) DEFAULT NULL,
  body       TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
--  SEED DATA
-- =====================================================================

INSERT INTO users (name, email, password_hash, role, location) VALUES
  ('MDLF Administrator', 'admin@mdlf.org', '$2b$10$B9VNK6jde8aegsLPYldm/ewYn3pcN0Jqb3RXykD5y3oqtMef9dq2e', 'admin', 'Jos, Plateau State'),
  ('Sample Disciple', 'disciple@mdlf.org', '$2b$10$4RvU1ZMFk8.rQ3dByOtKseqEJ2qeQEWom2rU0q6EPAHUEHIOfaxKu', 'member', 'Nigeria')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO settings (name, value) VALUES
  ('site_name', 'Mipo Dadang Leadership Foundation'),
  ('site_short', 'MDLF'),
  ('tagline', 'Raising reproducible leaders who restore people and communities.'),
  ('founder', 'Rev. Mipo Dadang'),
  ('mission', 'The Mipo Dadang Leadership Foundation exists to meet sociological and economic needs through sustainable approaches, renew the way people see one another, and mentor emerging leaders to handle the conflicts and crises of life with grace and competence.'),
  ('verse', 'And the things you have heard from me entrust to faithful people who will be able to teach others also.'),
  ('verse_ref', '2 Timothy 2:2'),
  ('contact_email', 'hello@mdlf.org'),
  ('contact_phone', '+234 800 000 0000'),
  ('contact_address', 'Jos, Plateau State, Nigeria'),
  ('give_account_name', 'Mipo Dadang Leadership Foundation'),
  ('give_bank', 'Bank name'),
  ('give_account_number', '0000000000'),
  ('brand_subtitle', 'Leadership Foundation'),
  ('brand_subtitle_visible', 'yes')
ON DUPLICATE KEY UPDATE value = VALUES(value);

INSERT INTO impact_stats (label, value, suffix, sort_order) VALUES
  ('Young people discipled & mentored', '206', '', 1),
  ('Youth leaders trained this June', '24', '', 2),
  ('Launched and serving since', '2024', '', 3),
  ('Lives turning to faith in Christ', '∞', '', 4);

-- ---------- Default editable pages ----------
INSERT INTO pages (title, slug, content, status, sort_order) VALUES
  ('Home', 'home', '', 'published', 1),
  ('About', 'about', '', 'published', 2),
  ('Our Work', 'our-work', '', 'published', 3),
  ('Discipleship', 'discipleship', '', 'published', 4),
  ('Give', 'give', '', 'published', 5),
  ('Contact', 'contact', '', 'published', 6)
ON DUPLICATE KEY UPDATE slug = slug;

-- ---------- Foundation objectives (the six commitments) ----------
INSERT INTO objectives (title, description, sort_order) VALUES
  ('Meet real needs', 'Address the sociological and economic needs of people through sustainable, dignifying approaches rather than dependency.', 1),
  ('Renew how we see people', 'Help people hold a right perspective toward one another, recovering the God-given dignity of every human being.', 2),
  ('Make reproducible disciples', 'Raise disciples who make disciples — entrusting what we have received to faithful people who can teach others also.', 3),
  ('Mentor emerging leaders', 'Walk alongside young leaders, forming the character and competence to carry the weight of leadership well.', 4),
  ('Build capacity for crisis', 'Equip people to manage the conflicts and crises of life with maturity, peace-making and appropriate measures.', 5),
  ('Serve the vulnerable', 'Provide intervention for vulnerable persons — widows, orphans and those in need — beyond temporal physical needs.', 6);

INSERT INTO posts (title, slug, excerpt, body, cover_image, status, published_at) VALUES
('24 Youth Leaders Complete Reproducible Discipleship Training',
 'reproducible-leaders-training-june',
 'On June 20–21, MDLF trained 24 youth leaders in a reproducible discipleship model designed to multiply across communities.',
 'On June 20 and 21, the Mipo Dadang Leadership Foundation gathered 24 youth leaders for an intensive Reproducible Leaders'' Discipleship Training.\n\nThe training was built on a simple conviction: a leader is not finished until they have raised another leader. Over two days the participants worked through the heart of discipleship, the practice of mentoring, and the discipline of handling conflict and crisis with maturity.\n\nThese 24 leaders now return to their churches, campuses and communities equipped not only to grow, but to reproduce — passing on what they received to others who will teach others also.',
 'training-group.jpeg', 'published', '2026-06-22 09:00:00'),

('206 Young People Reached Through Discipleship & Mentorship',
 '206-young-people-reached',
 'Between November 2024 and 2025, the foundation walked alongside 206 young people through structured discipleship and mentorship.',
 'In its first full year of structured outreach, the Mipo Dadang Leadership Foundation reached 206 young people through discipleship and mentorship.\n\nEach relationship was personal — not a programme to be completed, but a person to be known, encouraged and built up. Many came to faith in Christ through these relationships, and others found direction, healing and renewed purpose.\n\nThis is the heart of MDLF: people, restored and released to restore others.',
 'session-focus.jpeg', 'published', '2025-11-15 09:00:00'),

('The Foundation is Launched',
 'foundation-launched',
 'The Mipo Dadang Leadership Foundation was officially launched on March 30, 2024, with a vision to renew leaders and communities.',
 'On March 30, 2024, the Mipo Dadang Leadership Foundation was officially launched.\n\nFounded by Rev. Mipo Dadang, the foundation set out to address people''s sociological and economic needs through sustainable approaches, to help people hold a right perspective toward one another, and to provide renewal and mentorship to emerging leaders.\n\nWhat began as a vision has become a growing movement of disciples making disciples.',
 'hall-wide.jpeg', 'published', '2024-03-30 09:00:00');

-- ---------- Modules ----------
INSERT INTO modules (title, slug, summary, description, scripture, sort_order, status) VALUES
('The Heart of a Disciple', 'heart-of-a-disciple',
 'Before method comes the heart. Begin where discipleship begins — surrender, identity, and a love that reshapes how we see people.',
 'This first module lays the foundation. A disciple is not first a doer but a follower. We explore what it means to belong to Christ, to receive a new identity, and to begin seeing every person — including those society overlooks — through the eyes of their Maker.',
 'Luke 9:23', 1, 'published'),

('Reproducible Discipleship', 'reproducible-discipleship',
 'A leader is not finished until they have raised another. Learn the multiplication model at the core of MDLF.',
 'The model that shaped our June training. Discipleship that only adds is fragile; discipleship that multiplies endures. Here you learn to pass on what you have received so that those you teach can teach others also.',
 '2 Timothy 2:2', 2, 'published'),

('Christlike Character & Leadership', 'character-and-leadership',
 'Influence flows from character. Build the inner life that can carry the weight of leadership.',
 'Leadership built on gifting alone collapses under pressure. This module forms the character — integrity, humility, faithfulness — that allows God to trust a leader with people.',
 'Galatians 5:22-23', 3, 'published'),

('Managing Conflict & Crisis', 'conflict-and-crisis',
 'Life brings conflict and crisis. Develop the maturity and tools to handle them with appropriate, healing measures.',
 'Drawn directly from the foundation''s mandate to build the capacity of persons to manage the conflicts and crises of life. Practical, grounded, and rooted in peace-making.',
 'Matthew 5:9', 4, 'published'),

('A Right Perspective Toward People', 'right-perspective',
 'How we see people determines how we treat them. Recover the dignity of every human being.',
 'The foundation exists in part to help people hold a right perspective toward human beings. In a region marked by division, this module rebuilds the way we see the other.',
 'Genesis 1:27', 5, 'published'),

('Compassion in Action', 'compassion-in-action',
 'Discipleship that ignores the vulnerable is incomplete. Learn to serve widows, orphans and those in need.',
 'MDLF provides intervention for vulnerable persons to cope with life beyond temporal physical needs. This module moves compassion from feeling to faithful action.',
 'James 1:27', 6, 'published');

-- ---------- Lessons (module ids 1..6 in insert order) ----------
INSERT INTO lessons (module_id, title, slug, summary, content, duration_min, sort_order) VALUES
-- Module 1
(1, 'Who is a Disciple?', 'who-is-a-disciple', 'Defining discipleship before we attempt to do it.',
 'A disciple is a learner and a follower — someone whose life is being apprenticed to Jesus.\n\nIn this lesson we separate discipleship from mere church attendance or moral effort. To follow is to reorder our life around another. We will look at the first invitation Jesus gave — "follow me" — and what those two words asked of ordinary fishermen, tax collectors and women.\n\nReflect: What in my life is currently shaping me more than Christ is?', 12, 1),
(1, 'A New Identity', 'a-new-identity', 'You cannot give from an identity you have not received.',
 'Before a disciple does anything for God, they must receive who they are in God. This lesson explores the shift from striving to belonging — from working to be accepted to working because we are accepted.\n\nA leader operating from insecurity will use people; a leader secure in their identity can serve people. We begin here on purpose.', 14, 2),
(1, 'Seeing People Rightly', 'seeing-people-rightly', 'The first fruit of a renewed heart is a renewed gaze.',
 'When the heart changes, the eyes change. We begin to see the difficult person, the poor person, the person from the "other" group, as someone made in the image of God.\n\nThis lesson connects directly to the foundation''s call: to help people have a right perspective toward human beings.', 10, 3),

-- Module 2
(2, 'The Multiplication Principle', 'the-multiplication-principle', 'Addition grows a ministry; multiplication grows a movement.',
 'One leader who only gathers a crowd reaches a limited number. One leader who reproduces other leaders reaches generations.\n\nThis lesson unpacks 2 Timothy 2:2 — the four-generation chain of Paul, Timothy, faithful people, and others. We map what it would look like for you to become one link in a chain that outlives you.', 15, 1),
(2, 'Finding Faithful People', 'finding-faithful-people', 'Who you invest in matters as much as what you teach.',
 'Jesus prayed all night before choosing twelve. This lesson helps you discern the "faithful, available and teachable" people God has already placed around you — and how to invite them intentionally into a discipling relationship.', 13, 2),
(2, 'Passing It On', 'passing-it-on', 'A practical model you can reproduce next week.',
 'Here we put the model in your hands: a simple, repeatable rhythm of life-on-life discipleship that does not require a building, a budget, or a programme — only obedience and relationship. This is the engine behind the June training of 24 leaders.', 16, 3),

-- Module 3
(3, 'Character Over Charisma', 'character-over-charisma', 'What you build in private holds you up in public.',
 'Charisma can gather a crowd; only character can keep a trust. This lesson examines why God forms the leader before He uses the leader, and how to welcome the formation rather than resent it.', 12, 1),
(3, 'The Fruit of the Spirit at Work', 'fruit-of-the-spirit', 'Leadership as the visible fruit of an inner life.',
 'Love, joy, peace, patience, kindness, goodness, faithfulness, gentleness and self-control are not soft extras — they are the leadership competencies of the kingdom. We translate each into the daily work of leading people.', 14, 2),

-- Module 4
(4, 'Understanding Conflict', 'understanding-conflict', 'Conflict is not the enemy; mishandled conflict is.',
 'Conflict is a normal part of every family, team and community. This lesson reframes conflict as an opportunity for growth and reconciliation when handled with maturity, and traces where conflict comes from.', 13, 1),
(4, 'Becoming a Peacemaker', 'becoming-a-peacemaker', 'Practical tools for de-escalation and reconciliation.',
 'Peacemakers are called blessed. Here you gain practical, appropriate measures for managing crises — listening, naming the real issue, separating the person from the problem, and seeking restoration rather than victory.', 15, 2),
(4, 'Caring for the Self in Crisis', 'self-in-crisis', 'You cannot pour from an empty vessel.',
 'Crisis tests the leader as much as the led. This lesson builds the resilience, rest and spiritual rootedness a leader needs to remain steady when life shakes.', 11, 3),

-- Module 5
(5, 'The Dignity of Every Person', 'dignity-of-every-person', 'Every human being carries the image of God.',
 'In a context marked by ethnic and religious division, this truth is revolutionary: every person carries inherent dignity. This lesson confronts the labels and prejudices that diminish people and recovers a biblical vision of humanity.', 12, 1),
(5, 'Loving Across Lines', 'loving-across-lines', 'The disciple''s love refuses to stop at the boundary.',
 'Christ''s love crossed every social and ethnic line of his day. We learn to do the same — to see, honour and serve the person on the other side of the line as a neighbour, not a category.', 13, 2),

-- Module 6
(6, 'Religion God Accepts', 'religion-god-accepts', 'True religion shows up at the home of the widow and orphan.',
 'James defines pure religion as caring for orphans and widows in their distress. This lesson moves compassion from sentiment to a settled commitment, and looks at the foundation''s support for widows and orphans.', 12, 1),
(6, 'Beyond Temporal Needs', 'beyond-temporal-needs', 'Meeting the whole person — body, soul and spirit.',
 'MDLF provides intervention for vulnerable persons to cope with life beyond temporal physical needs. This lesson teaches holistic care: addressing the practical need while ministering to the dignity, hope and faith of the person.', 14, 2);

-- ---------- A few sample resources ----------
INSERT INTO resources (lesson_id, label, type, url, sort_order) VALUES
(1, 'Read: Luke 9:23-26', 'scripture', 'https://www.biblegateway.com/passage/?search=Luke+9%3A23-26', 1),
(1, 'Reflection worksheet (PDF)', 'pdf', '#', 2),
(4, 'Read: 2 Timothy 2:1-7', 'scripture', 'https://www.biblegateway.com/passage/?search=2+Timothy+2%3A1-7', 1),
(4, 'The multiplication chain (diagram)', 'link', '#', 2),
(6, 'Discipleship rhythm template (PDF)', 'pdf', '#', 1),
(14, 'Read: James 1:27', 'scripture', 'https://www.biblegateway.com/passage/?search=James+1%3A27', 1);
