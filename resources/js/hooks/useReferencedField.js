import { useMemo } from 'react';

export default function useReferencedField(all_data, config, config_key, fallback) {
	return useMemo(() => {
		const name = config_key in config
			? config[config_key]
			: fallback;
		return name in all_data
			? all_data[name]
			: null;
	}, [all_data, config, fallback]);
};
