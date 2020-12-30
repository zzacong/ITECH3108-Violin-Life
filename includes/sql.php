
<?php

$top3_violins_sql = "
  SELECT 
    of.violin_id, 
    vi.title, 
    COUNT(*) AS no_of_offer 
  FROM 
    offer of
  JOIN 
    violin vi 
  ON 
    of.violin_id = vi.id
  GROUP BY of.violin_id, vi.title
  ORDER BY no_of_offer DESC
  LIMIT 3
  ";

$sign_up_sql = "
  INSERT INTO `user` (
    name, username, email, password, location
  ) VALUES (
    :name, :username, :email, :hashed_password, :location
  )
  ";

$view_offers_sql = "
  SELECT 
    u.name AS owner_name, 
    u.username AS owner_username, 
    v.id AS violin_id, 
    v.title, 
    v.seeking, 
    v.description, 
    v.submitted, 
    o.id AS offer_id, 
    o.offer, 
    o.accepted, 
    o.submitted AS offered, 
    ou.name AS offerer_name, 
    ou.username AS offerer_username    
  FROM 
    `user` u
  JOIN
    violin v
  ON 
    u.id = v.user_id
  JOIN
    offer o
  ON 
    v.id = o.violin_id
  JOIN
    `user` ou
  ON 
    o.user_id = ou.id
  ORDER BY
    v.submitted DESC, o.submitted DESC
  ";

$make_offer_sql = "
  INSERT INTO offer (
    user_id, 
    violin_id, 
    offer
  )
  VALUES (
    :user_id, :violin_id, :offer
  )
  ";

$get_owner_sql = "
SELECT 
  u.username 
FROM 
  `user` u 
JOIN 
  violin v 
ON 
  u.id = v.user_id 
WHERE 
  v.id = :violin_id
";

$violin_details_sql = "
  SELECT 
    title, description, seeking 
  FROM 
    violin 
  WHERE 
    id = :violin_id
";

?>