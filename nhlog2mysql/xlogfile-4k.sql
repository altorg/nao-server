create table xlogfile (
    version char(10),
	variant int,
	points int,
	deathdnum int,
	deathlev int,
	maxlvl int,
	hp int,
	maxhp int,
	deaths int,
	deathdate int,
	birthdate int,
	uid int,
	role char(10),
	race char(10),
	gender char(3),
	align char(3),
	name char(20),
	death text,
    dumplog text,
	conduct int,
	turns int,
	starttime int,
	endtime int,
	gender0 char(3),
	align0 char(3)
);

create index idx_points on xlogfile(points);
create index idx_deathdate on xlogfile(deathdate);
create index idx_name on xlogfile(name);
create index idx_death on xlogfile(death(100));
create index idx_dumplog on xlogfile(dumplog(100));
create index idx_endtime on xlogfile(endtime);
create index idx_version on xlogfile(version);
create index idx_variant on xlogfile(variant);
create index idx_deathdnum on xlogfile(deathdnum);
create index idx_deathlev on xlogfile(deathlev);
create index idx_maxlvl on xlogfile(maxlvl);
create index idx_hp on xlogfile(hp);
create index idx_maxhp on xlogfile(maxhp);
create index idx_deaths on xlogfile(deaths);
create index idx_birthdate on xlogfile(birthdate);
create index idx_uid on xlogfile(uid);
create index idx_role on xlogfile(role);
create index idx_race on xlogfile(race);
create index idx_gender on xlogfile(gender);
create index idx_align on xlogfile(align);
create index idx_conduct on xlogfile(conduct);
create index idx_turns on xlogfile(turns);
create index idx_starttime on xlogfile(starttime);
create index idx_gender0 on xlogfile(gender0);
create index idx_align0 on xlogfile(align0);
