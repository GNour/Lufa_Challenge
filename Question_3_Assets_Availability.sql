SELECT DATE(a_act.created) as "date", COUNT(a_act.asset_id) as "number of unreturned asset"
FROM asset_activity as a_act
WHERE a_act.location_id = 3 AND a_act.asset_id 
NOT IN (SELECT asset_id FROM asset_activity WHERE asset_activity.created > a_act.created AND asset_id = a_act.asset_id)
GROUP BY DATE(a_act.created);

#BOUNUS

SELECT a.srl_nbr as "Asset serial number"
FROM asset as a, asset_activity as a_act
WHERE a_act.location_id = 3 
AND DATE(a_act.created) = "2016-01-11"
AND a_act.asset_id = a.asset_id
AND a_act.asset_id NOT IN (SELECT asset_id FROM asset_activity WHERE asset_activity.created > a_act.created AND asset_id = a_act.asset_id)
GROUP BY DATE(a_act.created);
