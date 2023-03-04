import { Either, left } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { map, catchError, of, Subject, Observable } from 'rxjs';
import { Error } from "../../../use-cases/common/errors/error";
import { Registration as RegistrationUseCase } from "../../../use-cases/user/registration";
import { Infrastructure } from '../../../use-cases/common/errors/infrastructure';

export namespace Registration {
  export class Emiter implements RegistrationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error[], RegistrationUseCase.Success>>
    ){}

    emit(either: Either<Error[], RegistrationUseCase.Success>): void {
      this._subject.next(either);
    }
  }

  export class Api implements RegistrationUseCase.Ports.Api {
    fetch(dto: RegistrationUseCase.Dto): Observable<Either<Error[], RegistrationUseCase.Success>> {
      return ajax({
        url: '/api/accounts/',
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: dto
      }).pipe(
        catchError((error) => {
          console.log(error);
          //need middleware or interceptor
          // if (error.status == 400) {
          //   return of(left(new Infrastructure.BadRequest(error.response.message)));
          // }
          // if (error.status == 404) {
          //   return of(left(new Infrastructure.NotFound(error.response.message)));
          // }
          // if (error.status == 500) {
          //   return of(left(new Infrastructure.InternalServerError(error.response.message)));
          // }
          // return of(left(new Infrastructure.InternalServerError(error.response.message)));
        })
      );
    }
  }
}
