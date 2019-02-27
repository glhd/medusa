import { useMedusaContext } from './useMedusaContext';

export default function useFieldContext(field) {
	const { name } = field;
	const { data, changed, touched, errors, onChange, onDependencies } = useMedusaContext();
	
	return {
		value: data[name],
		changed: changed[name],
		touched: touched[name],
		errors: name in errors ? errors[name] : [],
		onChange: onChange(name),
		onDependencies: onDependencies(name),
	};
}
